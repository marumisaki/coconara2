<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('==========');
debug('スクール一覧　');
debug('==========');
debugLogStart();

$dbh = dbConnect();
//================
// ページネーション
//----------------
$span = 5;
$activePage = (int)($_GET['page'] ?? 1);
debug('$activePage'.$activePage);
$minNum = ($activePage - 1) * $span;
$link = '';
$link .= rewriteGet(array('page'));
debug('$link:'.$link);
//===============
//　検索画面
//---------------
// 金額
$price = (!empty($_GET['price_id'])) ? $_GET['price_id'] : '';
// 言語
$language = (!empty($_GET['language_id'])) ? $_GET['language_id'] : '';
// コースタイプ
$courseType = (!empty($_GET['course_type_id'])) ? $_GET['course_type_id'] : '';
// 受講スタイル
$style = (!empty($_GET['style_id'])) ? $_GET['style_id'] : '';
// 立地
$access = (!empty($_GET['access_id'])) ? $_GET['access_id'] : '';
// 期間
$time = (!empty($_GET['time_id'])) ? $_GET['time_id'] : '';
// 教わり方
$method = (!empty($_GET['method_id'])) ? $_GET['method_id'] : '';
// パラメータに不正な値が入っているかチェック
if(empty($activePage)){
	debug('schoolList:不正な値が入りました。');
  header("Location:top.php");
	exit;
}else{
	$dbh = dbConnect();
	$activePage = (int)($_GET['page'] ?? 1);
	// 表示件数
	$listSpan = 5;
	// 現在の表示レコード先頭を算出
	$minNum = (($activePage-1)*$listSpan);
	$schools = getSchoolList($minNum, $price,$language,$courseType,$style,$access,$time,$method);
	if($schools['total'] == 0){

	}else if($activePage > $schools['total_page']){
		debug('schoolList:不正な値が入りました。');
  	header("Location:top.php");
	}
}
// DBからカテゴリデータを取得
$dbLanguageData = getLanguage();
$dbCourseTypeData = getCourseType();
$dbCourseData = getCourse();
$dbStyleData = getStyle();
$dbAccessData = getAccess();
$dbTimeData = getTime();
$dbMethodData = getMethod();
debug('$dbLanguageData：'.print_r($dbLanguageData,true));
debug('$dbCourseTypeData：'.print_r($dbCourseTypeData,true));
debug('$dbCourseData：'.print_r($dbCourseData,true));
debug('$dbStyleData：'.print_r($dbStyleData,true));
debug('$dbAccessData：'.print_r($dbAccessData,true));
debug('$dbTimeData：'.print_r($dbTimeData,true));
debug('$dbMethodData：'.print_r($dbMethodData,true));
// DBから商品データを取得
$schools = getSchoolList($minNum, $price,$language,$courseType,$style,$access,$time,$method);
debug('schoolList $schools：'.print_r($schools,true));

$pages = pagenate($link, $activePage, $span, $schools['total_page']);
debug(print_r($pages, true));

?>
<?php
	$siteTitle = 'スクール一覧';
	require('head.php');
?>
  <body class="body">

    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>

    <!-- メインコンテンツ -->
    <div class="container u-left">
    <div class="search-title u-flex-between u-mt_3l u-mb_xl">
          <div class="search-left">
            <span class="total-num"><?php echo sanitize($schools['total']); ?></span>件の商品が見つかりました
          </div>
          <div class="search-right">
            <span class="num"><?php echo (!empty($schools['data'])) ? $minNum+1 : 0; ?></span> - <span class="num"><?php echo $minNum+count($schools['data']); ?></span>件 / <span class="num"><?php echo sanitize($schools['total']); ?></span>件中
          </div>
        </div>
      <div class="u-flex-between">
			<!-- サイドバー -->
          <section id="sidebar" class="panel--lightblue panel--lightblue--side modal__parent">
					<form class="u-m_auto" name="" method="get">
						<h1 class="u-width-100">金額</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span></span>
							<select name="price_id">
								<option value="0" <?php if(getFormData($schools,'price_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<option value="1" <?php if(getFormData($schools,'price_id',true) == 1 ){ echo 'selected'; } ?> >金額が安い順</option>
								<option value="2" <?php if(getFormData($schools,'price_id',true) == 2 ){ echo 'selected'; } ?> >金額が高い順</option>
							</select>
						</div>
						<h1>言語</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span class="icn_select"></span>
							<select name="language_id">
								<option value="0" <?php if(getFormData($schools,'language_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbLanguageData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'language_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['language_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<h1>コース</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span></span>
							<select name="course_type_id">
								<option value="0" <?php if(getFormData($schools,'course_type_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbCourseTypeData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'course_type_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['course_type_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<h1>受講スタイル</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span class="icn_select"></span>
							<select name="style_id">
								<option value="0" <?php if(getFormData($schools,'style_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbStyleData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'style_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['style_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<h1>立地</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span class="icn_select"></span>
							<select name="access_id">
								<option value="0" <?php if(getFormData($schools,'access_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbAccessData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'access_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['access_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<h1>期間</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span class="icn_select"></span>
							<select name="time_id">
								<option value="0" <?php if(getFormData($schools,'time_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbTimeData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'time_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['time_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<h1>教わり方</h1>
						<div class="u-width-100 cp_ipselect cp_sl01">
							<span class="icn_select"></span>
							<select name="method_id">
								<option value="0" <?php if(getFormData($schools,'method_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
								<?php
									foreach($dbMethodData as $key => $val){
								?>
									<option value="<?php echo $val['id'] ?>" <?php if(getFormData($schools,'method_id',true) == $val['id'] ){ echo 'selected'; } ?> >
										<?php echo $val['method_name']; ?>
									</option>
								<?php
									}
								?>
							</select>
						</div>
						<input class="u-m_auto button--blue u-width-70 u-mt_xl u-mb_xl u-block" type="submit" value="検索">
					</form>
				</section>
				<?php require('modalSearch.php'); ?>

				<!-- Main -->
				<section id="main" class="u-width-75">
					 <?php
							foreach($schools['data'] as $key => $val):
						?>
							<div class="panel--white u-pl_4l u-pr_4l u-pt_4l u-pb_4l u-mb_l u-ml_m">
								<div class="u-flex-default">
									<div class="">
										<img class="bs-solid--sub" src="<?php echo sanitize($val['pic']); ?>" alt="<?php echo sanitize($val['school_name']); ?>">
									</div>
									<div class="u-ml_l u-width-60">
										<h2 class="text--xl"><?php echo sanitize($val['school_name']); ?></h2>
										<p><?php echo sanitize($val['information']);?></p>
									</div>
								</div>
								<div class="u-lineheight-2 u-mt_m">
									<p><span class="fc-font">目安料金　　：</span>約<?php echo sanitize($val['price_id']);?>円/月</p>
									<p><span class="fc-font">言語　　　　：</span>
									<?php
										$language_name = explode(',',$val['language_id']);
										debug('$language_name：'.print_r($language_name,true));
										debug('$dbLanguageData：'.print_r($dbLanguageData,true));
										$language_array = '';
										foreach($dbLanguageData as $vals){
											if(in_array($vals['id'], $language_name)){
												$language_array .= $vals['language_name'].",";
											}
										};
										echo rtrim($language_array,",");
									?></p>
									<p><span class="fc-font">コース　　　：</span>
									<?php
										debug('$dbCourseData：'.print_r($dbCourseData,true));
										$i=0;
										foreach($dbCourseData as $vals){
											if($i < 2){
												if($vals['s_id'] === $val['id']){
													$i++;
													echo $vals['course_name'].',';
												}
											}else if($i == 2){
												if($vals['s_id'] === $val['id']){
													$i++;
													echo $vals['course_name'];
												}
											}else if($i == 3){
												if($vals['s_id'] === $val['id']){
													$i++;
												}
											}else if($i == 4){
												$i++;
												echo ' など';
											}
										}
									?>
									</p>
									<p><span class="fc-font">受講スタイル：</span>
									<?php
										$style_name = explode(',',$val['style_id']);
										debug('$style_name：'.print_r($style_name,true));
										debug('$dbStyleData：'.print_r($dbStyleData,true));
										$style_array = '';
										foreach($dbStyleData as $vals){
											if(in_array($vals['id'], $style_name)){
												$style_array .= $vals['style_name'].",";
											}
										};
										echo rtrim($style_array,",");
									?>
									</p>
									<p><span class="fc-font">立地　　　　：</span>
									<?php
										$access_name = explode(',',$val['access_id']);
										debug('$access_name：'.print_r($access_name,true));
										debug('$dbAccessData：'.print_r($dbAccessData,true));
										$access_array = '';
										foreach($dbAccessData as $vals){
											if(in_array($vals['id'], $access_name)){
												$access_array .= $vals['access_name'].",";
											}
										};
										echo rtrim($access_array,",");
									?>
									</p>
									<p><span class="fc-font">目安期間　　：</span>
									<?php
										$time_name = explode(',',$val['time_id']);
										debug('$time_name：'.print_r($time_name,true));
										debug('$dbTimeData：'.print_r($dbTimeData,true));
										foreach($dbTimeData as $vals){
											if(in_array($vals['id'], $time_name)){
												echo $vals['time_name'];
											}
										};
									?>
									</p>
									<p><span class="fc-font">教わり方　　：</span>
									<?php
										$method_name = explode(',',$val['method_id']);
										debug('$method_name：'.print_r($method_name,true));
										debug('$dbMethodData：'.print_r($dbMethodData,true));
										$method_array = '';
										foreach($dbMethodData as $vals){
											if(in_array($vals['id'], $method_name)){
												$method_array .= $vals['method_name'].",";
											}
										};
										echo rtrim($method_array,",");
									?>
									</p>
								</div>
								<div class="u-mt_l u-width-100 u-m_auto u-flex-between u-center">
									<a class="fc-non u-middle button--blue u-width-30 u-ml_m u-mr_m" href="reviewList.php?s_id=<?php echo sanitize($val['id']); ?>">レビュー一覧</a>
									<a class="fc-non u-middle button--blue u-width-30 u-ml_m u-mr_m" href="<?php echo sanitize($val['url']); ?>">スクール公式HP</a>
									<a class="fc-non u-middle button--blue u-width-30 u-ml_m u-mr_m" href="reviewPost.php?s_id=<?php echo sanitize($val['id']); ?>">レビュー投稿する</a>
								</div>
							</div>
						<?php
							endforeach;
						?>
				</section>
      </div>
    </div>
		<div>
			<div class="pagenate panel--white">
				<?php foreach($pages as $key => $val){ echo $val; }?>
			</div>
		</div>
    <!-- footer -->
    <?php
      require('footer.php');
    ?>
