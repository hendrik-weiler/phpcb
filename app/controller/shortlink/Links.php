<?php

namespace Controller\shortlink;

class Links extends Controller
{
	public $form_generate_url;

	public function invalid() {
		print 'invalid link';
		exit();
	}

	public function get_execute($renderer, $request, $response)
	{
		$linksContainer = $renderer->document->getElementById('links');
		$this->initDB();

		$uid = $request->getUrlSegment(3);

		if(empty($uid)) {
			$this->invalid();
		}


		$result = $this->queryDB('SELECT counter,uid,link,created FROM link WHERE lgroup_id IN (SELECT id FROM linkGroup WHERE uid = "' . $this->escapeString($uid) . '") ORDER BY created DESC');
		$counter = 0;
		while($row = $result->fetchArray(SQLITE3_ASSOC))
		{
			$counter++;
			$link = HOST . 'shortlink/r/' . $row['uid'];

			$created = date('Y/d/m H:i',$row['created']);
			if($request->currentLanguage == 'de') {
				$created = date('d.m.Y H:i',$row['created']);
			}

			$node = $renderer->document->createFromHTML('
				<tr>
					<td><a class="shortlink" target="_blank" href="' . $link .'">'.$link.'</a> => <a class="reallink" target="_blank" href="' . $row['link'] .'">'.$row['link'].'</a></td>
					<td>' . $row['counter'] .'</td>
					<td>' . $created . '</td>
				</tr>
			');
			$linksContainer->appendChild($node);
		}

		if($counter == 0) {
			$this->invalid();
		}
	}

	public function post_execute($renderer, $request, $response)
	{
		$this->get_execute($renderer, $request, $response);

		if(!$request->checkCRSFToken()) {
			$response->redirect('/shortlink/links/' . $request->getUrlSegment(3));
			return;
		}

		if($this->form_generate_url->getValue() == '') {
			$error = $renderer->document->getElementById('error');
			$error->removeAttribute('hidden');
		} else {
			if (filter_var($this->form_generate_url->getValue(), FILTER_VALIDATE_URL) !== false) {
				$this->initDB();

				$uid = $request->getUrlSegment(3);
				$result = $this->queryDB('SELECT id FROM linkGroup WHERE uid = "' . $this->escapeString($uid) . '"');
				$row = $result->fetchArray(SQLITE3_ASSOC);
				if(count($row) == 1) {
					$url = $this->escapeString($this->form_generate_url->getValue());
					$this->execDB('INSERT INTO link VALUES (null,' . $row['id'] . ',"' . uniqid() . '","' . $url  . '",0,' . time() . ')');
					$response->redirect('/shortlink/links/' . $uid);
				} else {

				}
			} else {
				$error = $renderer->document->getElementById('error2');
				$error->removeAttribute('hidden');
			}
		}
	}
}