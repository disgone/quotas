<?php

class ParseController extends Controller {
	var $name = "Parse";
	var $uses = array('Quota', 'Project', 'Scan', 'Server');
	//var $autoRender = false;
	var $curServer;
	var $date;
	var $logfile;
	
	function process($server = null) {
		if($server == null) {
			$this->log("Missing server name.", "ParserError");
			exit();
		}

		$this->date = date("Ymd_his");
		$this->Server->recursive = -1;
		$this->curServer = $this->Server->findByName($server);
		
		//Set log file path.
		$this->logfile = "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date;
		//Get NSS report path for this server.
		$path = $this->curServer['Server']['path'];
		
		if(!file_exists($path)) {
			$this->log("Path not found [Server:$server] [Path:$path]", "ParserError");
			exit();
		}
			
		//Since Quota Server templates use square brackets as data fields
		//we can't escape through the use of the template using CDATA.
		//Escape ampersands after read, then load the corrected XML into the reader.
		$file = fopen($path, 'r');
		$contents = fread($file, filesize($path));
		$data = preg_replace("/&/", "&amp;", $contents);
		fclose($file);
		
		//Convert XML object to easy to parse array.
		App::import('Xml');
		$x = new XML($data);
		$data = Set::reverse($x);
		
		unset($file, $contents, $x);
		
		if($this->_readData($data)) {
			$this->log("===============LOG PARSING COMPLETED==============", $this->logfile);
			$this->_zipLog();
		}
		
		exit();
	}
	
	function _readData($data) {
		//If this is a new scan based on a the timestamp in the Quota report, proceed with attempt to log data.
		if($this->_isNew($data)) {
			$this->log("[I] New quota report found, starting parse...", $this->logfile);
			//Loop over each project listed in report.
			foreach($data['Data']['Quotas']['Quota'] as $key => $folder) {
				$this->log("[I] Parsing path [" . $folder['path'] . "]", $this->logfile);
				$pieces = $this->_splitPath($folder['path']);
				//We only want project folders, which are 3 levels deep,
				//we also want to ignore folders not inside of root project folders.
				//IE FSOR01\102 is valid, but FSOR01\Profiles will be ignored.
				if(count($pieces) > 2 && is_numeric($pieces[1])) {
					$this->log("[I] Valid path detected.", $this->logfile);
					$this->Project->create();
					$project = $this->_parseDetails($folder);
					
					$found = $this->Project->findByPath($project->path);
					//If there is a project number and it's not the the db alreay, add it.
					if($project->number != null && empty($found)) {
						if(is_numeric($project->Quota->consumed)) {
							$this->log("[NP] New project found, adding to database [" . $project->path . "]", $this->logfile);
							$save = $this->Project->save($project);
							if(!empty($save)) {
								$this->Quota->create();
								if(is_numeric($project->Quota->consumed) && is_numeric($project->Quota->allowance)) {
									$this->data['Quota']['allowance'] = $project->Quota->allowance;
									$this->data['Quota']['consumed'] = $project->Quota->consumed;
									$this->data['Quota']['project_id'] = $this->Project->id;
									$this->Quota->save($this->data);
									$this->log("[QS] Saving quota for project [" . $project->path . "]", "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date);
								}
							}
						}
					}
					else if($project->number != null && !empty($found)) {
						$this->Quota->create();
						$this->Project->id = $found['Project']['id'];
						if(is_numeric($project->Quota->consumed) && is_numeric($project->Quota->allowance)) {
							$this->data['Quota']['allowance'] = $project->Quota->allowance;
							$this->data['Quota']['consumed'] = $project->Quota->consumed;
							$this->data['Quota']['project_id'] = $found['Project']['id'];
							$this->Quota->save($this->data);
							$this->log("[QS] Saving quota for project [" . $project->path . "]", "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date);
						}
					}
					
					//Log an potential data errors.
					if(!is_numeric($project->Quota->allowance))
						$this->log("[E] Non numeric allowance value found for [" . $project->path . "] [allowance:" . $project->Quota->allowance . "]", "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date);
					if(!is_numeric($project->Quota->consumed))
						$this->log("[E] Non numeric usage value found for [" . $project->path . "] [usage:" . $project->Quota->consumed . "]", "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date);
				}
				else {
					$this->log("[E] Non-valid project folder [" . $folder['path'] . "]", "Parser/" . $this->curServer['Server']['name'] . "/Log_" . $this->date);
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	function _isNew($data) {
		$server =  $this->curServer['Server']['id'];
		$scantime = $data['Data']['Meta']['date'];
		$x = $this->Scan->find('first', array('conditions' => array('server_id' => $server, 'scantime' => $scantime), 'limit' => 1));
		
		if(empty($x)) {
			$this->Scan->create();
			$this->Scan->save(array('server_id' => $server, 'scantime' => $scantime));
			return true;
		}
		
		return false;
	}
	
	function _splitPath($path) {
		$path = preg_replace('/\\\\{2}/', "", $path);
		return explode("\\",$path);
	}
	
	//Parses the details of the projects from the folder paths.
	function _parseDetails(array $data) {
		$details = new stdClass();
		
		$pieces = $this->_splitPath($data['path']);
		//Project details
		$details->path = $data['path'];
		$details->number = $this->_getProjectNumber($pieces[2]);
		$details->name = $this->_getProjectName($pieces[2]);
		$details->server_id =  $this->curServer['Server']['id'];
		
		$limit = str_replace(',', '', $data['quota_limit']);
		$used = str_replace(',', '', $data['quota_used']);
		$details->Quota->allowance = is_numeric($limit) ? $limit : null;
		$details->Quota->consumed = is_numeric($used) ? $used : null;
		
		unset($limit, $used);
		
		return $details;
	}
	
	//Parses the project number out of the project folder.
	function _getProjectNumber($field) {
		$projectNumber = null;
		
		preg_match('/^[0-9]{4,5}([\._]{1})?([0-9]{3})?/', $field, $chunks);
		if(count($chunks) > 0)
			$projectNumber = preg_replace(array('/_$/', '/_/'), array('', '.'), $chunks[0]);

		return $projectNumber ? $projectNumber : null;
	}
	
	//Parses the project name from the project folder (if any).
	function _getProjectName($field) {
		$projectName = null;

		//Remove project number
		$projectName = preg_replace('/^[0-9]{4,5}([\._]{1})?([0-9]{3}?)?/', '', $field);
		//Format name
		$projectName = trim(preg_replace('/[_-]/', ' ', $projectName));
		
		return $projectName ? $projectName : null;
	}
	
	function _zipLog() {
		$x = new ZipArchive();
		$zip = LOGS . $this->logfile . ".zip";
		if($x->open($zip, ZIPARCHIVE::CREATE) === TRUE) {
			$x->addFile(LOGS . $this->logfile . ".log");
			$x->close();
			unlink(LOGS . $this->logfile . ".log");
		}
		
	}
}

?>