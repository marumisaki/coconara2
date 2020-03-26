<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

////ログイン認証
//require('userAuth.php');
//================================
// 画面処理
//================================
//ログイン認証
//require('auth.php');
// 画面表示用データ取得
//================================

// X $SESSION   O $_SESSION
//企業のIDはセッションのc_idに入れてもらいたいです。
//ユーザーIDはセッションのu_idに入っています。
$dbh = dbConnect();


$u_id = $_SESSION['u_id'];
$u_id=5;

$company = getCompany($dbh, $c_id);

$userData = getUser($dbh,$u_id);
// $user_dataの中に、getUserProfileで取得したデータを入れる。
//descriptionのデータが欲しい場合は、$user_data['description']で取得できる
//↑？？
$user_portfolio = getUserPortfolio($dbh,$u_id);
debug('$user_portfolio'.print_r($user_portfolio, true));
//function.phpまだ作ってない
$user_job = getHistory($dbh,$u_id);
debug('$user_job'.print_r($user_job, true));

$viewReview = getAllReviews($dbh,$u_id);
debug('$view_review'.print_r($viewReview,true));

$viewMethod = getMethod($dbh);
debug('viewMethod'.print_r($viewMethod, true));

//1掲示板１会社１枠

//GETぱらいらない
//u_idを使ってu_profileと比べる

//DBから掲示板とメッセージデータを取得

//GETする前にb_idを取ってこないといけない
//自分が関わるボードを全て取ってくる
//u_profile idが自分のものをboardsから！ここでSQL分をfunctionが必要
//bidを取ってきて、bidがある場合msg を取ってくる。

$b_id = getBordsFromUser($dbh,$u_id);
debug('$b_id'.print_r($b_id, true));

foreach ($b_id as $key => $val){
    $m_id = getMsgsAndBordsFromBid($dbh,$val['id']);
    $viewMsgs[] = $m_id;
//    $c_name = getCompanies array ()
    debug('$m_idの中身'.print_r($m_id, true));
}




debug('$viewMsgsの中身'.print_r ($viewMsgs, true));
//
//if(!empty($_POST['submit'])){
//     $c_id =$viewMsgs['c_id'];
//	$b_id = $b_id['id'];
//	debug('b_id'.print_r($b_id, true));
//  $b_id = $b_id['id'];
//  if(empty($b_id)){
//    $b_id = startBord($dbh, $u_id, $c_id);
//		debug('b_id:'.print_r($b_id, true));
//  }
//  header("location: msg.php?b_id=".$b_id);
//}


//パラメータに不正な値が入っているかチェック
if(empty($viewMsgs)){
error_log('エラー発生:指定ページに不正な値が入りました');
}else{
    $no_msg = 'メッセージがまだありません';
}


if(isset($_POST['delete'])){
  debug('POST送信があります。');
    $deleteMsg = $_POST['delete'];
    debug('$_POST[delete]'.print_r($_POST['delete'], true));
    debug('$deleteMsg'.print_r($deleteMsg,true));
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'UPDATE boards SET delete_flg = 1 WHERE id = :id';
    // データ流し込み
    $data = array(':id' => $deleteMsg);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリ実行成功の場合
    if($stmt){
     //セッション削除
      debug('セッション変数の中身：'.print_r($m_id,true));
    }else{
      debug('クエリが失敗しました。');
      $err_msg['common'] = MSG07;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
} 
// if(!empty($_POST['delete'])){
//     debug('削除したいものがあります');
//     try{
//         $b_id = getBordsFromUser($dbh,$u_id);
//        foreach ($b_id as $key => $val){
//    $m_id = getMsgsAndBordsFromBid($dbh,$val['id']);
//    $viewMsgs[] = $m_id;
////    $c_name = getCompanies array ()
//    debug('$m_idの中身'.print_r($m_id, true));
//}
//
//         $dbh = dbConnect();
//         $sql = 'UPDATE msgs SET delete_flg = 1 WHERE id = :m_id';
//         $data =  array(':m_id'=>$m_id);
//         $stmt = queryPost($dbh, $sql,$data);
//         
//         if($stmt){
//             debug('$m_idのメッセージを削除しました');
//             
//         }else{
//             debug('削除失敗しました');
//             $err_msg['common']=MSG07;
//         }
//     }catch (Exception $e){
//         error_log('エラー発生'.$e->getMessage());
//     }
// }
debug('画面表示終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');

?>

<!DOCTYPE html>
    <html lang="ja">
    <head>

    <meta charset="utf-8">
 <link rel="stylesheet" type="text/css" href="dist/style.css">
 </head>
 <?php
  $siteTitle = 'マイページ';?>
    <link rel="stylesheet" type="text/css" href="dist/style.css">
        <?php require('header.php'); ?>
        <?php require ('head.php'); ?>
  <body class="body u-m_auto">
<main class="l-container u-m_auto bg-basecolor">
<h1 class="panel--oblong u-center u-m_auto text--3l fw-bold u-mt_3l u-lineheight-2">マイページ</h1>
<section class="wrapper u-m_auto u-mb_xl u-mt_5l u-width-90 bg-non-0 u-pt_m u-pb_xl">
<div class="u-flex-column u-width-90 u-radius__s u-m_auto">
	<h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub">プロフィール</h1>
	
		<button class="button-edit u-align-endself button--blue u-block u-width-30 text--def u-mt_m u-mb_m ">
			<a href="userEdit.php?r_id=" class="u-white u-mt_l ">プロフィール編集する</a>
		</button>
		
		
		<div class="wrap profile-list  u-flex-between u-mt_m ">
			<img src="<?php echo $userData['pic']; ?>"class="img u-width-35  img__small img-fit" alt="">
			<div class="profile-list panel--lightblue u-width-60 u-pl_l u-pr_l u-pt_l">
				<div class="u-pb_m">
					<h2 class="sub-title text--l fw-bold">
					職務経歴
					</h2>
					<?php if(!empty($user_job)):foreach($user_job as $key =>$val): ?>
					<div>
                        <h3><?php echo $val['history_name']; ?></h3>
					<h2 class="sub-title text--l fw-bold">業務内容</h2>
					<p class="text text--def">
					<?php echo $val['detail']; ?>
					</p>
					</div>
					<?php
                    endforeach;
                    endif;
                    ?>
				</div>
				<div class="u-pb_m">
					<h2 class="text--l fw-bold">自己PR</h2>
					<p class="text text--def"><?php echo getFormdata($userData, 'goal');?></p>
				</div>
			</div>
		</div>
	</div>
 </section>

<section class="scout-list wrapper u-m_auto u-width-90 bg-non-0 u-pt_m u-pb_xl">
<h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub u-width-90 u-m_auto">スカウト一覧</h1>
<?php if(!empty($viewMsgs)):foreach($viewMsgs as $key => $val): ?>
<div class="wrapper scout-msgs panel--lightblue u-pl_l u-pr_l u-pt_l u-mt_xl u-width-90 u-m_auto">
	<div class="scout-msg">
	<div class="wrap-msg-info u-flex-between">
		<h2 class="sub-title text--l"><?php echo $val[0]['c_name']; ?></h2>
		<p class="u-right"><?php echo $val[0]['update_date']; ?></p>
		</div>
			<p class="text"><?php echo $val[0]['comment']; ?>
			 <?php if(empty($m_id)){ echo $no_msg;
                                            } ?></p>
			
	</div>
	<div class="u-flex-between btn-wrap u-m_auto u-block u-mt_m u-center">
	
		<button type ="button" name="button" class="edit button--blue u-width-20 text--def u-inline u-pl_m u-pr_m"><a href="msg.php?b_id= <?php echo $val[0]['b_id']; ?>" class="u-white">詳しく見る</a></button>

		<button class="edit button--blue u-width-20 text--def u-inline u-pl_m u-pr_m u-ml_l"><a href="<?php echo $val[0]['url'];?>" class="u-white">企業情報</a></button>
       <form method="post" class="post u-inline u-width-20">
           <button type="submit" name="delete" class="edit button--blue u-width-100 text--def u-inline u-pl_m u-pr_m u-ml_l u-white" value="<?php echo $val[0]['b_id']; ?>">削除</button></form>
	</div>
</div>
<?php
    endforeach;
    endif;
    ?>
</section>
<!--portfolio追加場面です。何を変えてもきちんとした表示ができません。。。PICがあるのだろうなというアイコンは出るのですが、画像表示されず、タイトル、その他は文字化けしてしまいます。。。
functionの中を$data= array()にしたら全部消えるのでfunctionの設定はあってるのかなと思うのですが。。。
dataが配列になっているか確認するものを入れても(! is_array($data))
illegal string offsetが治りません。。よろしくお願いいたします。-->

<section class="wrapper u-m_auto u-mb_xl u-mt_5l u-width-90 bg-non-0 u-pt_m">
	
	<div class="u-flex-column u-width-90 u-m_auto u-pb_xl">
	<h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub">ポートフォリオ</h1>
		<button class="button-edit u-align-endself button--blue u-block u-width-30 text--def u-mt_l "><a href="portfolioPost.php" class="u-white">ポートフォリオ追加</a></button>
		<div class="u-align-center u-mt_l  u-flex-between ">
			<?php
			if(!empty($user_portfolio)):
			foreach($user_portfolio as $key => $val):
			?>
		<div class="portfolio panel--lightblue panel--lightblue u-width-30 u-radius__s  u-mt_l   ">

			<a href="<?php echo $val['url']; ?>">
			<h2 class="portfolio-title text--xl u-white fw-bold u-block u-center"><?php echo $val['title']; ?></h2>
			<img class="img-fit img__small" src="<?php echo $val['pic']; ?>"></a>
		</div>
		<?php
			endforeach;
			endif;
		?>
		</div>
	</div>
</section>

<section class="wrapper u-m_auto u-mb_xl u-mt_5l u-width-90 bg-non-0 u-pt_m u-pb_4l">
	<h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub u-width-90 u-m_auto">レビュー</h1>
	<div class="u-flex-column u-width-90 u-m_auto">
	
	
	<?php
	if(!empty($viewReview)):foreach($viewReview as $key=>$val) :?>
	<div class="wrapper scout-msgs panel--lightblue u-pl_l u-pr_l u-pt_l u-mt_l u-radius__s u-width-90 u-m_auto">
		<div class="review">
			<p class="text"><span style="color: #7EA6F4" class="text--def fw-bold">スクール名：</span><?php echo $val['school_name']; ?></p>
			<p class="text"><span style="color: #7EA6F4" class="text--def  fw-bold">コース：</span><?php echo $val['course_name']; ?></p>
			<p class="text"><span style="color: #7EA6F4" class="text--def  fw-bold">金額：</span><?php echo $val['price'];?>円</p>
			<p class="text"><span style="color: #7EA6F4" class="text--def">通う形態：</span>
			<?php
			if(!empty($viewMethod)):foreach($viewMethod as $keys=>$vals):?>
			<?php if (strpos($val['method_id'], $vals['id']) === 0 || strpos($val['method_id'], $vals['id'])){?>
			<?php echo $vals['method_name']; ?>
			<?php }?>
			
			<?php
				endforeach;
				endif;
			?>
			<p class="text"><span style="color: #7EA6F4" class="text--def">良かったこと：</span><?php echo $val['good_comment']; ?></p>
			<button class="button-edit button--blue u-align-endself u-width-30 text--def u-mt_l u-block u-m_auto u-middle"><a href="reviewPost.php?r_id=<?php echo $val['id']; ?>" class="u-pl_l u-pr_l u-white">レビューを編集する</a></button>
		</div>
		
		
	</div>
	<?php
	endforeach;
	endif;
	?>
	</div>
	
</section>
</main>

<?php
require('footer.php');
?>