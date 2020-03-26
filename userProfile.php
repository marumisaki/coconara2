<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザープロフィール　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
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
$u_id = $_GET['u_id'];
$userData = getUser($dbh,$u_id);
// $user_dataの中に、getUserProfileで取得したデータを入れる。
//descriptionのデータが欲しい場合は、$user_data['description']で取得できる
//↑？？
$user_portfolio = getUserPortfolio($dbh,$u_id);

$user_job = getHistory($dbh,$u_id);
debug('$user_job'.print_r($user_job, true));

$viewReview = getAllReviews($dbh,$u_id);
debug('$view_review'.print_r($viewReview,true));

$viewMethod = getMethod($dbh);
debug('viewMethod'.print_r($viewMethod, true));

$class="";


if(!empty($_POST['submit'])){
  $c_id = $_SESSION['c_id'];

  $b_id = getBordsFromCompany($dbh, $u_id, $c_id);
	$b_id = $b_id['id'];
	debug('b_id'.print_r($b_id, true));
  $b_id = $b_id['id'];
  if(empty($b_id)){
    $b_id = startBord($dbh, $u_id, $c_id);
		debug('b_id:'.print_r($b_id, true));
  }
  header("location: msg.php?b_id=".$b_id);
}
debug('取得したユーザー情報'.print_r($userData,true));

if(!empty($_SESSION['c_id'])){
    $class = "";
    debug('$class'.print_r($class));
}else{
        if(!empty($_SESSION['u_id'])){
            $class = "u-hidden";
            debug('$class'.print_r($class,true));
        
    }else{
            $class= "u-hidden";
    }
        }



?>


<!DOCTYPE html>
    <html lang="ja">
    <head>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="dist/style.css">
              <?php
        $siteTitle = 'ユーザー詳細';
        ?>
        <?php require('header.php'); ?>
        <?php require ('head.php'); ?>
        </head>
 
   <body class="body u-m_auto">
          <main class="l-container u-m_auto">
          <h1 class="panel--oblong u-center u-m_auto text--3l fw-bold u-mt_5l u-pt_l u-pb_l"><?php echo $userData['u_name']; ?>のプロフィール</h1>

                            <section class="wrapper u-m_auto u-mb_xl u-mt_5l">
                   
                       <h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub">プロフィール</h1>
                       <form method="post">
                    
                      <button type="submit" name="submit" value="スカウトする" class="button-edit button--blue u-width-20 text--def u-block u-pl_m u-pr_m u-m_auto u-mt_l <?php echo $class; ?>">スカウトする</button>
                       </form>
                        <div class="u-flex-around">
                       <div class="profile u-block">
                           <div class="wrap profile-list u-flex-around u-mt_xl ">
                       <img src="<?php echo $userData['pic']; ?>"class="img__small" alt="">
                       <div class="profile-list panel--lightblue u-width-70 u-ml_xl u-pl_l u-pr_l u-pt_l">
                        <div class="u-pb_m">
                           <?php
                            if(!empty($user_job)): foreach ($user_job as $key => $val): ?>
                            <h2 class="sub-title text--l fw-bold">
職務経歴:<span class="text text--l u-pb_m fw-bold">
                                      <?php echo $val['history_name']; ?>
                                  </span>
                            </h2>
                                   <h2 class="sub-title text--l fw-bold u-mt_m">業務内容:</h2>
                            <p class="text"><?php echo $val['detail']; ?></p>
                                  <?php endforeach; ?>
                                  <?php endif; ?>
                                  
                            <p class="text--def">
                                <?php echo $userData['description']; ?>
                            </p>
                        </div>
                        <div class="u-pb_m">
                            <h2 class="text--l fw-bold">自己PR</h2>
                            <p class="text text--def"><?php echo $userData['goal']; ?></p>
                        </div>
                      </div>
                      </div>
                       </div>

                    </div>
                       
                </section>
                
               <section class="wrapper u-m_auto u-mb_xl u-mt_5l">
                  <h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub">ポートフォリオ</h1>
                    <div class="u-flex-center u-mt_xl">
                         <?php
                        if(!empty($user_portfolio)):
                        foreach($user_portfolio as $key => $val):
                        ?>
                       <div class="portfolio panel--lightblue u-width-30 u-flex-center u-mr_l">
                            <a href="<?php echo $val['url']; ?>">
                             <h2 class="portfolio-title text--xl u-white fw-bold u-block u-center"><?php echo $val['title']; ?></h2>
                            <img class="img-fit portfolio-sample" src="<?php echo $val['pic']; ?>"></a>
                          
                        </div>
                         <?php
                        endforeach;
                        endif;
                        ?>
                    </div>
                    
                </section>
                <section class="wrapper u-m_auto u-mb_xl u-mt_5l">
                   <h1 class="u-sub u-center text--xl u-radius__s u-mb_m u-mt_xl u-pt_m u-pb_m fw-bold panel--sub">レビュー</h1>
                    <?php
                    if(!empty($viewReview)):foreach($viewReview as $key=>$val) :?>

                     <div class="wrapper scout-msgs panel--lightblue u-pl_l u-pr_l u-pt_l u-mt_xl">
                         <div class="review">
                        <p class="text"><span style="color: #7EA6F4" class="c-text--def fw-bold">スクール名：</span><?php echo $val['school_name']; ?></p>
                        <p class="text"><span style="color: #7EA6F4" class="c-text--def  fw-bold">コース：</span><?php echo $val['course_name']; ?></p>
                        <p class="text"><span style="color: #7EA6F4" class="c-text--def  fw-bold">金額：</span><?php echo $val['price'];?>円</p>
                        <?php
                            if(!empty($viewMethod)):foreach($viewMethod as $keys=>$vals):?>
                            <?php if($val['method_id']==$vals['id']){?>
                        <p class="text"><span style="color: #7EA6F4" class="c-text--def">通う形態：</span><?php echo $vals['method_name'];?></p>
                        <?php }?>
                        <?php
                            endforeach;
                            endif;
                            ?>
                            <p class="text"><span style="color: #7EA6F4" class="c-text--def">良かったこと：</span><?php echo $val['good_comment']; ?></p>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    endif;
                    ?>
                </section>
               <button class="button-edit button--blue u-width-20 text--def u-block u-m_auto u-pl_m u-pr_m">
		<a class="u-white  text--l u-pt_m u-pb_m" href="schoolList.php">TOPへ</a>
              </button>

                <?php
                    require('footer.php');
        ?>