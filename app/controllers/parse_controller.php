<?php

class ParseController extends Controller {
	var $name = "Parse";
	var $uses = array('Quota', 'Project', 'Scan');
	//var $autoRender = false;
	
	function process() {
		$path = TMP . 'quota/Quota.xml';
		
		if(!file_exists($path))
			exit();
			
		//Since Quota Server templates use square brackets as data fields
		//we can't escape through the use of the template using CDATA.
		//Escape ampersands after read, then load the corrected XML into the reader.
		$file = fopen($path, 'r');
		$contents = fread($file, filesize($path));
		$data = preg_replace("/&/", "&amp;", $contents);
		
		//Convert XML object to easy to parse array.
		App::import('Xml');
		$x = new XML($data);
		$data = Set::reverse($x);
		
		$this->_readData($data);
		
		$this->set('data', '');
	}
	
	function _readData($data) {
		if($this->_isNew($data)) {
			foreach($data['Data']['Quotas']['Quota'] as $key => $folder) {
				$pieces = $this->_splitPath($folder['path']);
				
				//We only want project folders, which are 3 levels deep,
				//we also want to ignore folders not inside of root project folders.
				//IE FSOR01\102 is valid, but FSOR01\Profiles will be ignored.
				if(count($pieces) > 2 && is_numeric($pieces[1])) {
					$project = $this->_parseDetails($folder);
					//If there is a project number and it's not the the db alreay, add it.
					if($project->number != null && !$this->Project->findByPath($project->path)) {
						$this->Project->create();
						$save = $this->Project->save($project);
						if(!empty($save)) {
							$this->Quota->create();
							$this->data['Quota']['allowance'] = $project->Quota->allowance;
							$this->data['Quota']['consumed'] = $project->Quota->consumed;
							$this->data['Quota']['project_id'] = $this->Project->id;
							$this->Quota->save($this->data);
						}
					}
					else if($project->number != null && $cur = $this->Project->findByPath($project->path)) {
						$this->Quota->create();
						$this->data['Quota']['allowance'] = $project->Quota->allowance;
						$this->data['Quota']['consumed'] = $project->Quota->consumed;
						$this->data['Quota']['project_id'] = $cur['Project']['id'];
						$this->Quota->save($this->data);
					}
				}
			}
		}
	}
	
	function _isNew($data) {
		$server = $data['Data']['Meta']['server'];
		$date = $data['Data']['Meta']['date'];
		$x = $this->Scan->find('first', array('conditions' => array('server' => $server, 'time' => $date), 'limit' => 1));
		
		if(empty($x)) {
			$this->Scan->create();
			$this->Scan->save(array('server' => $server, 'time' => $date));
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
		
		$details->path = $data['path'];
		$pieces = $this->_splitPath($data['path']);
		$details->number = $this->_getProjectNumber($pieces[2]);
		$details->name = $this->_getProjectName($pieces[2]);
		$details->server = $pieces[0];
		
		$details->Quota->allowance = is_numeric(str_replace(',', '', $data['quota_limit'])) ? str_replace(',', '', $data['quota_limit']) : null;
		$details->Quota->consumed = is_numeric(str_replace(',', '', $data['quota_used'])) ? str_replace(',', '', $data['quota_used']) : null;
		
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
}

?>