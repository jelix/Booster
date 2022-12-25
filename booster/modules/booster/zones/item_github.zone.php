<?php
/**
* @package   booster
* @subpackage booster
* @author    Florian Lonqueu-Brochard, Laurent Jouanneau
* @copyright 2011 Florian Lonqueu-Brochard, 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license    All rights reserved
*/

class item_githubZone extends jZone {
    protected $_tplname='zone.item_github';

    protected $_useCache = true;
    protected $_cacheTimeout = 3600;//1 heure

    public function __construct($params=array())
    {
       $params['lang'] = jApp::config()->locale;
       parent::__construct($params);
    }

    protected function _prepareTpl()
    {
		$url_repo = $this->param('url_repo');

		$m = array();
		preg_match('#https?://github.com/([^/]*)/([^/]*)/?(.+)?#', $url_repo, $m);

        if(empty($m[1]) OR empty($m[2]) OR !empty($m[3])) {
            \jLog::log('invalid github repo url '.$url_repo);
            $this->cancelZone();
            return;
        }

        $user = $m[1];
        $repo = $m[2];

        $filtered = preg_replace('@[^a-zA-Z0-9_]@', '_', array($repo, $user));
		$key = 'github_'.$filtered[0].'_'.$filtered[1].'_';

    	$forks = jCache::get($key.'forks');
    	$watchers = jCache::get($key.'watchers');
    	$update = jCache::get($key.'update');

        $github = new \JelixBooster\BoosterGithub($user, $repo);
    	if ($forks === false || $watchers === false || $update === false){
			$infos = $github->getRepoInfos();
            if(!$infos){
                $this->cancelZone();
                return;
            }
			$forks = $infos->forks;
			$watchers = $infos->watchers;
			$update = $infos->updated_at;
			jCache::set($key.'forks', $forks, 86400);//1jour
			jCache::set($key.'watchers', $watchers, 86400);//1jour
			jCache::set($key.'update', $update, 86400);//1jour
    	}
    	$this->_tpl->assign('forks', $forks);
    	$this->_tpl->assign('watchers', $watchers);
    	$this->_tpl->assign('update', $update);


    	$activity = jCache::get($key.'activity');
    	if($activity === false){
    		$activity = $github->getRepositoryActivity();
            if(!$activity){
                $this->cancelZone();
                return;
            }
    		jCache::set($key.'activity', $activity, 129600);//1jour et demi
    	}
    	$this->_tpl->assign('activity',$activity);

    }

    protected function cancelZone()
    {
        $this->_tpl->assign('not_ok', true);
    }
}
