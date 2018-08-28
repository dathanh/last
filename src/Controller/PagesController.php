<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path) {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if ($page == 'home') {
            $this->home();
        }
        if ($page == 'processGetContent') {
            $this->processGetContent();
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function home() {
        $contentText = $this->getContent(694);
        $this->set('content', $contentText);
        $this->set('chapter', 694);
    }

    public function processGetContent() {
        if ($this->request->is('post')) {
            $chapter = $this->request->getData('chapter');
            $contentText = $this->getContent($chapter);

            return $this->actionSuccess($chapter + 1, $contentText);
        }
    }

    public function getContent($chapter) {
        $starClass = '<div class="content" id="js-truyencv-content" style="font-size: 26px; line-height: 140%; font-family: \'Palatino Linotype\', sans-serif; word-wrap: break-word;"> ';
        $endClasss = '<div class="footer">';
        $content = file_get_contents("http://truyencv.com/vo-dich-thien-ha/chuong-$chapter/");
        $contentText = '';
        $output = preg_match_all("/$starClass(.*)$endClasss/is", $content, $matches);
        $noise1 = '<div class="text-center"><div id="abd_itvcplayer"><p></p>]</div><script type="text/javascript">var abd_media="media.adnetwork.vn";var abd_width=500;var abd_height=281;var abd_skip=7;var abd_flash=true;var abd_popup=true;var abd_wid=1515641312;var abd_zid=1515641399;var abd_content_id="#abd_itvcplayer";var abd_position=0;</script><script src="http://media.adnetwork.vn/assets/js/abd.inpage.preroll.v2.js" type="text/javascript"></script></div><br /> <br />';
        $noise2 = '<br /> <br /> <br /> <p>&nbsp;</p><p>truyện hay, nhiều gái, main khá bá <a href="http://truyencv.com/thau-huong-cao-thu/">Thâu Hương Cao Thủ</a></p> </div>';
        $noise3 = '<br />';
        $noise4 = '</p>';
        $noise5 = '<p>';
        $noise6 = '<div style="margin: 50px 5px 0 0;display:inline;float:right"><div id="mediaplayer"></div></div>';
        $noise7 = '<div class="text-center"><div id="abd_itvcplayer">]</div><script type="text/javascript">var abd_media="media.adnetwork.vn";var abd_width=500;var abd_height=281;var abd_skip=7;var abd_flash=true;var abd_popup=true;var abd_wid=1515641312;var abd_zid=1515641399;var abd_content_id="#abd_itvcplayer";var abd_position=0;</script><script src="http://media.adnetwork.vn/assets/js/abd.inpage.preroll.v2.js" type="text/javascript"></script></div>';
        $noise8 = '<a href="http://truyencv.com/thau-huong-cao-thu/">Thâu Hương Cao Thủ</a>';

        $contentText = isset($matches[1][0]) ? $matches[1][0] : '';
        $contentText = str_replace($noise1, '', $contentText);
        $contentText = str_replace($noise2, '', $contentText);
        $contentText = str_replace($noise3, '', $contentText);
        $contentText = str_replace($noise4, '', $contentText);
        $contentText = str_replace($noise5, '', $contentText);
        $contentText = str_replace($noise6, '', $contentText);
        $contentText = str_replace($noise7, '', $contentText);
        $contentText = str_replace($noise8, '', $contentText);

        return $contentText;
    }

}
