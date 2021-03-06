<?php
use Behat\Behat\Context\Step\Given;
use	Behat\Behat\Context\Step\When;
use Behat\Behat\Context\Step\Then;

App::uses('Fabricate', 'Fabricate.Lib');

$steps->Given('/^"([^"]*)"としてログインしている$/', function($world, $user){
	//throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^記事が(\d+)件登録されている$/', function($world, $num){
	Fabricate::create('Post', $num, function($data,$world){
		return ['title' => $world->sequence('title', function($i){ return "タイトル{$i}"; })];
	});
});

$steps->When('/^自分の投稿を一覧表示する$/', function($world){
	return [
		new When('"' . Router::url(['controller' => 'posts', 'action' => 'index', 'user_account' => 'hoge']) . '" を表示している'),
	];
});

$steps->Then('/^ページ(\d+)に投稿が新しい順で(\d+)件表示されている$/',function($world,$page,$count){
	//アクティブなページ番号が異なればページ遷移する
	$active = $world->getSession()->getPage()->find('css', '.pagination .active a');
	if($active && ($page != $active->getText())){
		$world->getSession()->getPage()->find('css', '.pagination')->clickLink($page);
	}
//	//記事の件数が期待どおりか
//	$world->assertSession()->elementsCount('css', 'article > section', $count);
//	//記事の一覧からタイトルを抽出する
//	$titles = array_map(function($section){
//		return $section->find('css','h1')->getText();
//	},$world->getSession()->getPage()->findAll('css','article > section'));
//	//タイトルが降順かどうか
//	$expect = array_chunk(array_map(function($i){
//		return "タイトル{$i}";
//	}, range($world->getModel('Post')->find('count'),1)),5)[$page-1];
//	assertEquals($expect,$titles);
});