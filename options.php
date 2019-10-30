<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
 
 

function optionsframework_option_name() {

	// 从样式表获取主题名称
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'sakura'
 * with the actual text domain for your theme.  
 *
 * Frame from: https://github.com/devinsays/options-framework-plugin/
 */

function optionsframework_options() {
	// 测试数据
	$test_array = array(
		'one' => __('1', 'sakura'),
		'two' => __('2', 'sakura'),
		'three' => __('3', 'sakura'),
		'four' => __('4', 'sakura'),
		'five' => __('5', 'sakura'),
		'six' => __('6', 'sakura'),
		'seven' => __('7', 'sakura')
	);
		

	// 复选框数组
	$multicheck_array = array(
		'one' => __('1', 'sakura'),
		'two' => __('2', 'sakura'),
		'three' => __('3', 'sakura'),
		'four' => __('4', 'sakura'),
		'five' => __('5', 'sakura')
	);

	// 复选框默认值
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// 背景默认值
	$background_defaults = array(
		'color' => '',
		'image' => 'https://view.moezx.cc/images/2018/12/23/knights-of-the-frozen-throne-8k-qa.jpg',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// 版式默认值
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );
		
	// 版式设置选项
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => '普通','bold' => '粗体' ),
		'color' => false
	);

	// 将所有分类（categories）加入数组
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// 将所有标签（tags）加入数组
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}


	// 将所有页面（pages）加入数组
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// 如果使用图片单选按钮, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();

	//基本设置
	$options[] = array(
		'name' => __('基本设置', 'sakura'),
		'type' => 'heading');
	
	$options[] = array(
		'name' => __('站点名称', 'sakura'),
		'desc' => __('樱花庄的白猫', 'sakura'),
		'id' => 'site_name',
		'std' => '',
		'type' => 'text');	

	$options[] = array(
		'name' => __('作者', 'sakura'),
		'desc' => __('Mashiro', 'sakura'),
		'id' => 'author_name',
		'std' => '',
		'type' => 'text');	

	$options[] = array(
        'name' => __("主题风格", 'sakura'),
        'id' => 'theme_skin',
        'std' => "#FE9600",
        'desc' => __('自定义主题颜色', ''),
        'type' => "color"
	);
	
	$options[] = array(
		'name' => __('切换主题菜单透明度', 'sakura'),
		'desc' => __('调整切换主题菜单透明度，值越小越透明,默认透明度0.8', 'sakura'),
		'id' => 'sakura_skin_alpha',
		'std' => '0.8',
		'type' => 'select',
		'options'=>array(
			'0'=> __('全透明',''),
			'0.1'=> __('透明度0.1',''),
			'0.2'=> __('透明度0.2',''),
			'0.3'=> __('透明度0.3',''),
			'0.4'=> __('透明度0.4',''),
			'0.5'=> __('透明度0.5',''),
			'0.6'=> __('透明度0.6',''),
			'0.7'=> __('透明度0.7',''),
			'0.8'=> __('透明度0.8',''),
			'0.9'=> __('透明度0.9',''),
			'1'=> __('不透明',''),
		));	

	$options[] = array(
		'name' => __('切换网页背景', 'sakura'),
		'desc' => __('前台切换网页背景，共8个url，使用空格分隔，顺序对应前台切换主题按钮位置（按钮顺序从左至右，从上至下）,如不需要背景则填写对应位置为none。<strong>注意：如果主题是从v3.2.3及以下更新过来的，请务必将本配置页的【其他】标签下的【版本控制】参数修改为任意新值！</strong>
', 'sakura'),
		'id' => 'sakura_skin_bg',
		'std' => 'none https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/sakura.png https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/plaid2dbf8.jpg https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/star02.png https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/kyotoanimation.png https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/dot_orange.gif https://api.mashiro.top/bing/ https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.2/other-sites/api-index/images/me.png',
		'type' => 'textarea');
	
	$options[] = array(
	   'name' => __('个人头像', 'sakura'),
	   'desc' => __('最佳高度尺寸130*130px。', 'sakura'),
	   'id' => 'focus_logo',
	   'type' => 'upload');
       
     $options[] = array(
		'name' => __('文字版LOGO', 'sakura'),
		'desc' => __('首页不显示上方的头像，而是显示一段文字（此处留空则使用上方的头像）。文字建议不要过长，16个字节左右为宜。', 'sakura'),
		'id' => 'focus_logo_text',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('logo', 'sakura'),
		'desc' => __('最佳高度尺寸40px。', 'sakura'),
		'id' => 'akina_logo',
		'type' => 'upload');	
	
	$options[] = array(
		'name' => __('Favicon', 'sakura'),
		'desc' => __('就是浏览器标签栏上那个小 logo，填写url', 'sakura'),
		'id' => 'favicon_link',
		'std' => '/wp-content/themes/Sakura/images/favicon.ico',
		'type' => 'text');

	$options[] = array(
		'name' => __('自定义关键词和描述', 'sakura'),
		'desc' => __('开启之后可自定义填写关键词和描述', 'sakura'),
		'id' => 'akina_meta',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('网站关键词', 'sakura'),
		'desc' => __('各关键字间用半角逗号","分割，数量在5个以内最佳。', 'sakura'),
		'id' => 'akina_meta_keywords',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('网站描述', 'sakura'),
		'desc' => __('用简洁的文字描述本站点，字数建议在120个字以内。', 'sakura'),
		'id' => 'akina_meta_description',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('展开导航菜单', 'sakura'),
		'desc' => __('勾选开启，默认收缩', 'sakura'),
		'id' => 'shownav',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('头部装饰图', 'sakura'),
		'desc' => __('默认开启，勾选关闭，显示在文章页面，独立页面以及分类页', 'sakura'),
		'id' => 'patternimg',
		'std' => '0',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => __('搜索按钮', 'sakura'),
		'id' => 'top_search',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));

	$options[] = array(
		'name' => __('首页文章风格', 'sakura'),
		'id' => 'post_list_style',
		'std' => "imageflow",
		'type' => "radio",
		'options' => array(
			'standard' => __('标准', ''),
			'imageflow' => __('图文', '')
		));

	$options[] = array(
		'name' => __('首页文章特色图（仅对标准风格生效）', 'sakura'),
		'id' => 'list_type',
		'std' => "round",
		'type' => "radio",
		'options' => array(
			'round' => __('圆形', ''),
			'square' => __('方形', '')
		));	

	$options[] = array(
		'name' => __('首页文章特色图对齐方式（仅对图文风格生效，默认左右交替）', 'sakura'),
		'id' => 'feature_align',
		'std' => "alternate",
		'type' => "radio",
		'options' => array(
			'left' => __('向左对齐', ''),
			'right' => __('向右对齐', ''),
			'alternate' => __('左右交替', '')
		));	
        
    $options[] = array(
		'name' => __('默认文章特色图', 'sakura'),
		'desc' => __('在未设置文章特色图的情况下展示的默认图像，留空则调用本地随机封面（要展示的图片放入 /wp-content/themes/Sakura/feature/gallery/ 目录）', 'sakura'),
		'id' => 'default_feature_image',
		'std' => 'https://api.mashiro.top/feature/',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('评论收缩', 'sakura'),
		'id' => 'toggle-menu',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
        
    $options[] = array(
		'name' => __('文章末尾显示作者信息？', 'sakura'),
		'desc' => __('勾选启用', 'sakura'),
		'id' => 'show_authorprofile',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('分页模式', 'sakura'),
		'id' => 'pagenav_style',
		'std' => "ajax",
		'type' => "radio",
		'options' => array(
			'ajax' => __('ajax加载', ''),
			'np' => __('上一页和下一页', '')
		));
	
	$options[] = array(
		'name' => __('自动加载下一页', 'sakura'),
		'desc' => __('（秒）设置自动加载下一页时间，默认不自动加载', 'sakura'),
		'id' => 'auto_load_post',
		'std' => '233',
		'type' => 'select',
		'options'=>array(
			'0'=> __('0秒',''),
			'1'=> __('1秒',''),
			'2'=> __('2秒',''),
			'3'=> __('3秒',''),
			'4'=> __('4秒',''),
			'5'=> __('5秒',''),
			'6'=> __('6秒',''),
			'7'=> __('7秒',''),
			'8'=> __('8秒',''),
			'9'=> __('9秒',''),
			'10'=> __('10秒',''),
			'233'=> __('233秒,即不倒计时自动加载',''),
		));	

	$options[] = array(
		'name' => __('博主描述', 'sakura'),
		'desc' => __('一段自我描述的话', 'sakura'),
		'id' => 'admin_des',
		'std' => '一沙一世界，一花一天堂。君掌盛无边，刹那成永恒。',
		'type' => 'textarea');	

	$options[] = array(
		'name' => __('页脚信息', 'sakura'),
		'desc' => __('页脚说明文字，支持HTML代码', 'sakura'),
		'id' => 'footer_info',
		'std' => 'Copyright &copy; by Mashiro All Rights Reserved.',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Google 统计代码', 'sakura'),
		'desc' => __('UA-xxxxx-x', 'sakura'),
		'id' => 'google_analytics_id',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('站长统计（不建议使用）', 'sakura'),
		'desc' => __('填写统计代码，将被隐藏', 'sakura'),
		'id' => 'site_statistics',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('自定义CSS样式', 'sakura'),
		'desc' => __('直接填写CSS代码，不需要写style标签', 'sakura'),
		'id' => 'site_custom_style',
		'std' => '',
		'type' => 'textarea');		

		
	//第一屏
	$options[] = array(
		'name' => __('第一屏', 'sakura'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('总开关', 'sakura'),
		'desc' => __('默认开启，勾选关闭', 'sakura'),
		'id' => 'head_focus',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('社交信息', 'sakura'),
		'desc' => __('默认开启，勾选关闭，显示头像、签名、SNS', 'sakura'),
		'id' => 'focus_infos',
		'std' => '0',
		'type' => 'checkbox');
        
    $options[] = array(
    'name' => __('社交信息样式', 'sakura'),
    'id' => 'social_style',
    'std' => "v2",
    'type' => "radio",
    'options' => array(
        'v2' => __('与签名合并', ''),
        'v1' => __('独立成行', '')
    ));

	$options[] = array(
		'name' => __('全屏显示', 'sakura'),
		'desc' => __('默认开启，勾选关闭', 'sakura'),
		'id' => 'focus_height',
		'std' => '0',
		'type' => 'checkbox'); 	
	
	 $options[] = array(
		'name' => __('开启视频', 'sakura'),
		'desc' => __('勾选开启', 'sakura'),
		'id' => 'focus_amv',
		'std' => '0',
		'type' => 'checkbox');

	 $options[] = array(
		'name' => __('Live', 'sakura'),
		'desc' => __('勾选开启，视频自动续播，需要开启Pjax功能', 'sakura'),
		'id' => 'focus_mvlive',
		'std' => '0',
		'type' => 'checkbox');

	 $options[] = array(
		'name' => __('视频地址', 'sakura'),
		'desc' => __('视频的来源地址，该地址拼接下面的视频名，地址尾部不需要加斜杠', 'sakura'),
		'id' => 'amv_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('视频名称', 'sakura'),
		'desc' => __('abc.mp4 ，只需要填写视频文件名 abc 即可，多个用英文逗号隔开如 abc,efg ，无需在意顺序，因为加载是随机的抽取的 ', 'sakura'),
		'id' => 'amv_title',
		'std' => '',
		'type' => 'text');

    $options[] = array(
 		'name' => __('封面图', 'sakura'),
 		'desc' => __('此处留空则使用内置API（将需要随机展示的图片放入 /cover/gallery/ 目录）', 'sakura'),
 		'id' => 'cover_img',
 		'std' => '',
 		'type' => 'text');
        
	$options[] = array(
		'name' => __('背景图滤镜', 'sakura'),
		'id' => 'focus_img_filter',
		'std' => "filter-nothing",
		'type' => "radio",
		'options' => array(
			'filter-nothing' => __('无', ''),
			'filter-undertint' => __('浅色', ''),
			'filter-dim' => __('暗淡', ''),
			'filter-grid' => __('网格', ''),
			'filter-dot' => __('点点', '')
		));

    $options[] = array(
		'name' => __('是否开启聚焦', 'sakura'),
		'desc' => __('默认开启', 'sakura'),
		'id' => 'top_feature',
		'std' => '1',
		'type' => 'checkbox');	
        
	$options[] = array(
		'name' => __('聚焦样式', 'sakura'),
		'id' => 'top_feature_style',
		'std' => "left_and_right",
		'type' => "radio",
		'options' => array(
			'left_and_right' => __('左右交替', ''),
			'bottom_to_top' => __('从下往上', '')
		));    

	$options[] = array(
		'name' => __('聚焦标题', 'sakura'),
		'desc' => __('默认为聚焦，你也可以修改为其他，当然不能当广告用！不允许！！', 'sakura'),
		'id' => 'feature_title',
		'std' => '聚焦',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('聚焦图一', 'sakura'),
		'desc' => __('尺寸257px*160px', 'sakura'),
		'id' => 'feature1_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图一标题', 'sakura'),
		'desc' => __('聚焦图一标题', 'sakura'),
		'id' => 'feature1_title',
		'std' => 'feature1',
		'type' => 'text');	

	$options[] = array(
		'name' => __('聚焦图一描述', 'sakura'),
		'desc' => __('聚焦图一描述', 'sakura'),
		'id' => 'feature1_description',
		'std' => 'Description goes here 1',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图一链接', 'sakura'),
		'desc' => __('聚焦图一链接', 'sakura'),
		'id' => 'feature1_link',
		'std' => '#',
		'type' => 'text');		
		
	$options[] = array(
		'name' => __('聚焦图二', 'sakura'),
		'desc' => __('尺寸257px*160px', 'sakura'),
		'id' => 'feature2_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图二标题', 'sakura'),
		'desc' => __('聚焦图二标题', 'sakura'),
		'id' => 'feature2_title',
		'std' => 'feature2',
		'type' => 'text');

	$options[] = array(
		'name' => __('聚焦图二描述', 'sakura'),
		'desc' => __('聚焦图二描述', 'sakura'),
		'id' => 'feature2_description',
		'std' => 'Description goes here 2',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图二链接', 'sakura'),
		'desc' => __('聚焦图二链接', 'sakura'),
		'id' => 'feature2_link',
		'std' => '#',
		'type' => 'text');
			
	$options[] = array(
		'name' => __('聚焦图三', 'sakura'),
		'desc' => __('尺寸257px*160px', 'sakura'),
		'id' => 'feature3_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图三标题', 'sakura'),
		'desc' => __('聚焦图三标题', 'sakura'),
		'id' => 'feature3_title',
		'std' => 'feature3',
		'type' => 'text');	

	$options[] = array(
		'name' => __('聚焦图三描述', 'sakura'),
		'desc' => __('聚焦图三描述', 'sakura'),
		'id' => 'feature3_description',
		'std' => 'Description goes here 3',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图三链接', 'sakura'),
		'desc' => __('聚焦图三链接', 'sakura'),
		'id' => 'feature3_link',
		'std' => '#',
		'type' => 'text');

		
	//文章页
	$options[] = array(
		'name' => __('文章页', 'sakura'),
		'type' => 'heading');
        
    $options[] = array(
		'name' => __('文章样式', 'sakura'),
		'id' => 'entry_content_theme',
		'std' => "sakura",
		'type' => "radio",
		'options' => array(
			'sakura' => __('默认样式', ''),
			'github' => __('GitHub 样式', ''),
		));

	$options[] = array(
		'name' => __('文章点赞', 'sakura'),
		'id' => 'post_like',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
		
	$options[] = array(
		'name' => __('文章分享', 'sakura'),
		'id' => 'post_share',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
	
	$options[] = array(
		'name' => __('上一篇下一篇', 'sakura'),
		'id' => 'post_nepre',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
		
	$options[] = array(
		'name' => __('博主信息', 'sakura'),
		'id' => 'author_profile',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));

	$options[] = array(
		'name' => __('支付宝打赏', 'sakura'),
		'desc' => __('支付宝二维码', 'sakura'),
		'id' => 'alipay_code',
		'type' => 'upload');

	$options[] = array(
		'name' => __('微信打赏', 'sakura'),
		'desc' => __('微信二维码', 'sakura'),
		'id' => 'wechat_code',
		'type' => 'upload');	

		
	//社交选项
	$options[] = array(
		'name' => __('社交网络', 'sakura'),
		'type' => 'heading');	
	
	$options[] = array(
		'name' => __('微信', 'sakura'),
		'desc' => __('微信二维码', 'sakura'),
		'id' => 'wechat',
		'type' => 'upload');
	
    $options[] = array(
		'name' => __('新浪微博', 'sakura'),
		'desc' => __('新浪微博地址', 'sakura'),
		'id' => 'sina',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('腾讯QQ', 'sakura'),
		'desc' => __('tencent://message/?uin={{QQ号码}}，如tencent://message/?uin=123456', 'sakura'),
		'id' => 'qq',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Telegram', 'sakura'),
		'desc' => __('Telegram链接', 'sakura'),
		'id' => 'telegram',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('QQ空间', 'sakura'),
		'desc' => __('QQ空间地址', 'sakura'),
		'id' => 'qzone',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('GitHub', 'sakura'),
		'desc' => __('GitHub地址', 'sakura'),
		'id' => 'github',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Lofter', 'sakura'),
		'desc' => __('lofter地址', 'sakura'),
		'id' => 'lofter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('BiliBili', 'sakura'),
		'desc' => __('B站地址', 'sakura'),
		'id' => 'bili',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('优酷视频', 'sakura'),
		'desc' => __('优酷地址', 'sakura'),
		'id' => 'youku',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('网易云音乐', 'sakura'),
		'desc' => __('网易云音乐地址', 'sakura'),
		'id' => 'wangyiyun',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Twitter', 'sakura'),
		'desc' => __('推特地址', 'sakura'),
		'id' => 'twitter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Facebook', 'sakura'),
		'desc' => __('脸书地址', 'sakura'),
		'id' => 'facebook',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google+', 'sakura'),
		'desc' => __('G+地址', 'sakura'),
		'id' => 'googleplus',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('简书', 'sakura'),
		'desc' => __('简书地址', 'sakura'),
		'id' => 'jianshu',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('CSDN', 'sakura'),
		'desc' => __('CSND社区地址', 'sakura'),
		'id' => 'csdn',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('知乎', 'sakura'),
		'desc' => __('知乎地址', 'sakura'),
		'id' => 'zhihu',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('邮箱-用户名', 'sakura'),
		'desc' => __('name@domain.com 的 name 部分，前端仅具有js运行环境时才能获取完整地址，可放心填写', 'sakura'),
		'id' => 'email_name',
		'std' => '',
		'type' => 'text');

    $options[] = array(
		'name' => __('邮箱-域名', 'sakura'),
		'desc' => __('name@domain.com 的 domain.com 部分', 'sakura'),
		'id' => 'email_domain',
		'std' => '',
		'type' => 'text');	

	//后台配置
	$options[] = array(
		'name' => __('后台配置', 'sakura'),
		'type' => 'heading' );
        
    //后台面板自定义配色方案
    $options[] = array(
    'name' => __('后台面板自定义配色方案', 'sakura'),
    'desc' => __('你可以在下面自行设计后台面板（/wp-admin/）样式，不过在开始之前请到<a href="/wp-admin/profile.php">这里</a>将配色方案改为自定义（Custom）。<br><b>Tip: </b>如何搭配颜色？或许<a href="https://mashiro.top/color-thief/">这个</a>可以帮到你。', 'sakura'),
    'id' => 'scheme_tip',
    'std' => '',
    'type' => 'typography ');
    
    $options[] = array(
        'name' => __("面板主色调A", 'sakura'),
        'id' => 'dash_scheme_color_a',
        'std' => "#c6742b",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板主色调B", 'sakura'),
        'id' => 'dash_scheme_color_b',
        'std' => "#d88e4c",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板主色调C", 'sakura'),
        'id' => 'dash_scheme_color_c',
        'std' => "#695644",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板主色调D", 'sakura'),
        'id' => 'dash_scheme_color_d',
        'std' => "#a19780",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板图标配色——base", 'sakura'),
        'id' => 'dash_scheme_color_base',
        'std' => "#e5f8ff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板图标配色——focus", 'sakura'),
        'id' => 'dash_scheme_color_focus',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', ''),
        'type' => "color"
    );
    
    $options[] = array(
        'name' => __("面板图标配色——current", 'sakura'),
        'id' => 'dash_scheme_color_current',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', ''),
        'type' => "color"
    );
		
	$options[] = array(
		'name' => __('其他自定义面板样式(CSS)', 'sakura'),
		'desc' => __('如果还需要对面板其他样式进行调整可以把style放到这里', 'sakura'),
		'id' => 'dash_scheme_css_rules',
		'std' => '#adminmenu .wp-has-current-submenu .wp-submenu a,#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,#adminmenu .wp-submenu a,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a,.folded #adminmenu .wp-has-current-submenu .wp-submenu a{color:#f3f2f1}body{background-image:url(https://view.moezx.cc/images/2019/04/21/windows10-2019-4-21-i3.jpg);background-size:cover;background-repeat:no-repeat;background-attachment:fixed;}#wpcontent{background:rgba(255,255,255,.8)}',
		'type' => 'textarea');

    $options[] = array(
		'name' => __('后台登陆界面背景图', 'sakura'),
		'desc' => __('该地址为空则使用默认图片', 'sakura'),
		'id' => 'login_bg',
		'type' => 'upload');
        
    $options[] = array(
 		'name' => __('后台登陆界面logo', 'sakura'),
 		'desc' => __('用于登录界面显示', 'sakura'),
 		'id' => 'logo_img',
 		'std' => $imagepath.'mashiro-logo-s.png',
 		'type' => 'upload');    
    
    $options[] = array(
    'name' => __('登陆/注册相关设定', 'sakura'),
    'desc' => __('', 'sakura'),
    'id' => 'login_tip',
    'std' => '',
    'type' => 'typography ');
    
	$options[] = array(
		'name' => __('指定登录地址', 'sakura'),
		'desc' => __('强制不使用后台地址登陆，填写新建的登陆页面地址，比如 http://www.xxx.com/login【注意】填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况', 'sakura'),
		'id' => 'exlogin_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('指定注册地址', 'sakura'),
		'desc' => __('该链接使用在登录页面作为注册入口，建议填写', 'sakura'),
		'id' => 'exregister_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('允许用户注册', 'sakura'),
		'desc' => __('勾选开启，允许用户在前台注册', 'sakura'),
		'id' => 'ex_register_open',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('登录后自动跳转', 'sakura'),
		'desc' => __('勾选开启，管理员跳转至后台，用户跳转至主页', 'sakura'),
		'id' => 'login_urlskip',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('注册验证（仅前端，后端强制开启）', 'sakura'),
		'desc' => __('勾选开启滑动验证', 'sakura'),
		'id' => 'login_validate',
		'std' => '0',
		'type' => 'checkbox');	

    //CDN 优化
	$options[] = array(
		'name' => __('CDN', 'sakura'),
		'type' => 'heading' );
        
	$options[] = array(
		'name' => __('图片库 CDN', 'sakura'),
		'desc' => __('注意：填写格式为 http(s)://你的CDN域名/。<br>也就是说，原路径为 http://your.domain/wp-content/uploads/2018/05/xx.png 的图片将从 http://你的CDN域名/2018/05/xx.png 加载', 'sakura'),
		'id' => 'qiniu_cdn',
		'std' => '',
		'type' => 'text');  
        
    $options[] = array(
		'name' => __('本地调用前端库（lib.js、lib.css）', 'sakura'),
		'desc' => __('前端库不走 jsDelivr，不建议启用', 'sakura'),
		'id' => 'jsdelivr_cdn_test',
		'std' => '0',
		'type' => 'checkbox'); 
        
    $options[] = array(
		'name' => __('本地调用主题 js、css 文件（sakura-app.js、style.css）', 'sakura'),
		'desc' => __('主题的 js、css 文件不走 jsDelivr，DIY 时请开启', 'sakura'),
		'id' => 'app_no_jsdelivr_cdn',
		'std' => '0',
		'type' => 'checkbox'); 
        
    	//其他
	$options[] = array(
		'name' => __('其他', 'sakura'),
		'type' => 'heading' );
        
    $options[] = array(
    'name' => __('关于', 'sakura'),
    'desc' => __('Theme Sakura v'.SAKURA_VERSION.'  |  <a href="https://2heng.xin/theme-sakura/">主题说明</a>  |  <a href="https://github.com/mashirozx/Sakura/">源码</a><a href="https://github.com/mashirozx/Sakura/releases/latest"><img src="https://img.shields.io/github/release/mashirozx/Sakura.svg?style=flat-square" alt="GitHub release"></a>', 'sakura'),
    'id' => 'theme_intro',
    'std' => '',
    'type' => 'typography ');
    
    $options[] = array(
		'name' => "检查更新",
		'desc' => '<a href="https://github.com/mashirozx/Sakura/releases/latest">下载最新版</a>',
		'id' => "release_info",
		'std' => "tag",
		'type' => "images",
		'options' => array(
			'tag' => 'https://img.shields.io/github/release/mashirozx/Sakura.svg?style=flat-square',
            'tag2' => 'https://img.shields.io/github/commits-since/mashirozx/Sakura/v'.SAKURA_VERSION.'.svg?style=flat-square'
        )
	);

	$options[] = array(
		'name' => __('页脚悬浮播放器', 'sakura'),
		'desc' => __('如果不需要播放器留空即可。填写网易云音乐的「歌单」ID，eg：https://music.163.com/#/playlist?id=2288037900的ID是2288037900', 'sakura'),
		'id' => 'playlist_id',
		'std' => '2288037900',
		'type' => 'text');
        
	$options[] = array(
		'name' => __('版本控制', 'sakura'),
		'desc' => __('用于更新前端 cookie 及浏览器缓存，可使用任意字符串', 'sakura'),
		'id' => 'cookie_version',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('开启PJAX局部刷新（建议开启）', 'sakura'),
		'desc' => __('原理与Ajax相同', 'sakura'),
		'id' => 'poi_pjax',
		'std' => '0',
		'type' => 'checkbox');
    
    $options[] = array(
		'name' => __('开启NProgress加载进度条', 'sakura'),
		'desc' => __('默认不开启，勾选开启', 'sakura'),
		'id' => 'nprogress_on',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('开启公告', 'sakura'),
		'desc' => __('默认不显示，勾选开启', 'sakura'),
		'id' => 'head_notice',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('公告内容', 'sakura'),
		'desc' => __('公告内容，文字超出142个字节将会被滚动显示（移动端无效），一个汉字 = 3字节，一个字母 = 1字节，自己计算吧', 'sakura'),
		'id' => 'notice_title',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('首页不显示的分类文章', 'sakura'),
		'desc' => __('填写分类ID，多个用英文“ , ”分开', 'sakura'),
		'id' => 'classify_display',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('图片展示分类', 'sakura'),
		'desc' => __('填写分类ID，多个用英文“ , ”分开', 'sakura'),
		'id' => 'image_category',
		'std' => '',
		'type' => 'text');
        
    $options[] = array(
		'name' => __('统计接口', 'sakura'),
		'id' => 'statistics_api',
		'std' => "theme_build_in",
		'type' => "radio",
		'options' => array(
			'wp_statistics' => __('WP-Statistics 插件（专业性统计，可排除无效访问）', ''),
			'theme_build_in' => __('主题内建（简单的统计，计算每一次页面访问请求）', '')
		));
        
    $options[] = array(
		'name' => __('统计数据显示格式', 'sakura'),
		'id' => 'statistics_format',
		'std' => "type_1",
		'type' => "radio",
		'options' => array(
			'type_1' => __('23333 次访问（默认）', ''),
			'type_2' => __('23,333 次访问（英式）', ''),
			'type_3' => __('23 333 次访问（法式）', ''),
			'type_4' => __('23k 次访问（中式）', ''),
		));
        
    $options[] = array(
		'name' => __('启用实时搜索', 'sakura'),
		'desc' => __('前台实现实时搜索，调用 Rest API 每小时更新一次缓存，可在 functions.php 里手动设置缓存时间'),
		'id' => 'live_search',
		'std' => '0',
		'type' => 'checkbox');
        
    $options[] = array(
		'name' => __('实时搜索包含评论', 'sakura'),
		'desc' => __('在实时搜索中搜索评论（如果网站评论数量太多不建议开启）'),
		'id' => 'live_search_comment',
		'std' => '0',
		'type' => 'checkbox');
        
    $options[] = array(
		'name' => __('启用 baguetteBox', 'sakura'),
		'desc' => __('默认禁用，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">请阅读说明</a>', 'sakura'),
		'id' => 'image_viewer',
		'std' => '0',
		'type' => 'checkbox');	
        
    $options[] = array(
		'name' => __('文章内图片启用 lazyload', 'sakura'),
		'desc' => __('默认启用', 'sakura'),
		'id' => 'lazyload',
		'std' => '1',
		'type' => 'checkbox');	
        
    $options[] = array(
		'name' => __('lazyload spinner', 'sakura'),
		'desc' => __('图片加载时要显示的占位图，填写图片 url', 'sakura'),
		'id' => 'lazyload_spinner',
		'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg',
		'type' => 'text');
        
    $options[] = array(
		'name' => __('是否开启剪贴板版权标识', 'sakura'),
		'desc' => __('复制超过30个字节时自动向剪贴板添加版权标识，默认开启', 'sakura'),
		'id' => 'clipboard_copyright',
		'std' => '1',
		'type' => 'checkbox');	
        
	$options[] = array(
		'name' => __('发件地址前缀', 'sakura'),
		'desc' => __('用于发送系统邮件，在用户的邮箱中显示的发件人地址，不要使用中文，默认系统邮件地址为 bibi@你的域名', 'sakura'),
		'id' => 'mail_user_name',
		'std' => 'bibi',
		'type' => 'text');

	$options[] = array(
		'name' => __('允许私密评论', 'sakura'),
		'desc' => __('允许用户设置自己的评论对其他人不可见', 'sakura'),
		'id' => 'open_private_message',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('机器人验证', 'sakura'),
		'desc' => __('开启机器人验证', 'sakura'),
		'id' => 'norobot',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('评论UA信息', 'sakura'),
		'desc' => __('勾选开启，用户的浏览器，操作系统信息', 'sakura'),
		'id' => 'open_useragent',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('开启多说插件支持', 'sakura'),
		'desc' => __('多说已经凉了', 'sakura'),
		'id' => 'general_disqus_plugin_support',
		'std' => '0',
		'type' => 'checkbox');
        
    $options[] = array(
		'name' => __('时区调整', 'sakura'),
		'desc' => __('如果评论出现时差问题在这里调整，填入一个整数，计算方法：实际时间=显示错误的时间-你输入的整数（单位：小时）', 'sakura'),
		'id' => 'time_zone_fix',
		'std' => '0',
		'type' => 'text');
        
	return $options;
}