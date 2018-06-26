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
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  
 *
 * Frame from: https://github.com/devinsays/options-framework-plugin/
 */

function optionsframework_options() {
	// 测试数据
	$test_array = array(
		'one' => __('1', 'options_framework_theme'),
		'two' => __('2', 'options_framework_theme'),
		'three' => __('3', 'options_framework_theme'),
		'four' => __('4', 'options_framework_theme'),
		'five' => __('5', 'options_framework_theme'),
		'six' => __('6', 'options_framework_theme'),
		'seven' => __('7', 'options_framework_theme')
	);
		

	// 复选框数组
	$multicheck_array = array(
		'one' => __('椎名真白', 'options_framework_theme'),
		'two' => __('时崎狂三', 'options_framework_theme'),
		'three' => __('西木野真姬', 'options_framework_theme'),
		'four' => __('黑泽露比', 'options_framework_theme'),
		'five' => __('渡边曜', 'options_framework_theme')
	);

	// 复选框默认值
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// 背景默认值
	$background_defaults = array(
		'color' => '',
		'image' => '',
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
		'name' => __('基本设置', 'options_framework_theme'),
		'type' => 'heading');
	
	$options[] = array(
		'name' => __('站点名称', 'options_framework_theme'),
		'desc' => __('樱花庄的白猫', 'options_framework_theme'),
		'id' => 'site_name',
		'std' => '',
		'type' => 'text');	

	$options[] = array(
		'name' => __('作者', 'options_framework_theme'),
		'desc' => __('Mashiro', 'options_framework_theme'),
		'id' => 'author_name',
		'std' => '',
		'type' => 'text');	

	$options[] = array(
        'name' => __("主题风格", 'akina'),
        'id' => 'theme_skin',
        'std' => "#FE9600",
        'desc' => __('自定义主题颜色（此功能没有优化，建议使用#FE9600）', ''),
        'type' => "color"
	);
	
	$options[] = array(
	   'name' => __('个人头像', 'options_framework_theme'),
	   'desc' => __('最佳高度尺寸130*130px。', 'options_framework_theme'),
	   'id' => 'focus_logo',
	   'type' => 'upload');
		
	$options[] = array(
		'name' => __('logo', 'options_framework_theme'),
		'desc' => __('最佳高度尺寸40px。', 'options_framework_theme'),
		'id' => 'akina_logo',
		'type' => 'upload');	
	
	$options[] = array(
		'name' => __('Favicon', 'options_framework_theme'),
		'desc' => __('就是浏览器标签栏上那个小 logo，填写url', 'options_framework_theme'),
		'id' => 'favicon_link',
		'std' => '/wp-content/themes/Sakura/images/favicon.ico',
		'type' => 'text');

	$options[] = array(
		'name' => __('自定义关键词和描述', 'options_framework_theme'),
		'desc' => __('开启之后可自定义填写关键词和描述', 'options_framework_theme'),
		'id' => 'akina_meta',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('网站关键词', 'options_framework_theme'),
		'desc' => __('各关键字间用半角逗号","分割，数量在5个以内最佳。', 'options_framework_theme'),
		'id' => 'akina_meta_keywords',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('网站描述', 'options_framework_theme'),
		'desc' => __('用简洁的文字描述本站点，字数建议在120个字以内。', 'options_framework_theme'),
		'id' => 'akina_meta_description',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('展开导航菜单', 'options_framework_theme'),
		'desc' => __('勾选开启，默认收缩', 'options_framework_theme'),
		'id' => 'shownav',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('头部装饰图', 'options_framework_theme'),
		'desc' => __('默认开启，勾选关闭，显示在文章页面，独立页面以及分类页', 'options_framework_theme'),
		'id' => 'patternimg',
		'std' => '0',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => __('搜索按钮', 'akina'),
		'id' => 'top_search',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));

	$options[] = array(
		'name' => __('首页文章风格', 'akina'),
		'id' => 'post_list_style',
		'std' => "standard",
		'type' => "radio",
		'options' => array(
			'standard' => __('标准', ''),
			'imageflow' => __('图文', '')
		));

	$options[] = array(
		'name' => __('首页文章特色图（仅对标准风格生效）', 'akina'),
		'id' => 'list_type',
		'std' => "round",
		'type' => "radio",
		'options' => array(
			'round' => __('圆形', ''),
			'square' => __('方形', '')
		));	
		
	$options[] = array(
		'name' => __('评论收缩', 'akina'),
		'id' => 'toggle-menu',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	

    $options[] = array(
		'name' => __('评论信息栏宽度调整', 'options_framework_theme'),
		'desc' => __('不知道为什么有人老会搞出问题，求你们不要用那些莫名其妙的插件。。如果出问题了在这里调整，输入一个介于0到100的数字以调整宽度，以免出现框框换行的情况，正常情况下97左右比较正常吧。。如果本来就显示正常的请务必留空！', 'options_framework_theme'),
		'id' => 'comment_info_box_width',
		'std' => '',
		'type' => 'text');	
        
    $options[] = array(
		'name' => __('文章末尾显示作者信息？', 'options_framework_theme'),
		'desc' => __('勾选启用', 'options_framework_theme'),
		'id' => 'show_authorprofile',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('分页模式', 'akina'),
		'id' => 'pagenav_style',
		'std' => "ajax",
		'type' => "radio",
		'options' => array(
			'ajax' => __('ajax加载', ''),
			'np' => __('上一页和下一页', '')
		));

	$options[] = array(
		'name' => __('博主描述', 'options_framework_theme'),
		'desc' => __('一段自我描述的话', 'options_framework_theme'),
		'id' => 'admin_des',
		'std' => '一沙一世界，一花一天堂。君掌盛无边，刹那成永恒。',
		'type' => 'textarea');	

	$options[] = array(
		'name' => __('页脚信息', 'options_framework_theme'),
		'desc' => __('页脚说明文字，支持HTML代码', 'options_framework_theme'),
		'id' => 'footer_info',
		'std' => '&copy; 2018',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Google 统计代码', 'options_framework_theme'),
		'desc' => __('UA-xxxxx-x', 'options_framework_theme'),
		'id' => 'google_analytics_id',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('站长统计（不建议使用）', 'options_framework_theme'),
		'desc' => __('填写统计代码，将被隐藏，如需要在下方填写链接地址', 'options_framework_theme'),
		'id' => 'site_statistics',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('自定义CSS样式', 'options_framework_theme'),
		'desc' => __('直接填写CSS代码，不需要写style标签', 'options_framework_theme'),
		'id' => 'site_custom_style',
		'std' => '',
		'type' => 'textarea');		

		
	//第一屏
	$options[] = array(
		'name' => __('第一屏', 'options_framework_theme'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('总开关', 'options_framework_theme'),
		'desc' => __('默认开启，勾选关闭', 'options_framework_theme'),
		'id' => 'head_focus',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('社交信息', 'options_framework_theme'),
		'desc' => __('默认开启，勾选关闭，显示头像、签名、SNS', 'options_framework_theme'),
		'id' => 'focus_infos',
		'std' => '0',
		'type' => 'checkbox');
        
    $options[] = array(
    'name' => __('社交信息样式', 'akina'),
    'id' => 'social_style',
    'std' => "v2",
    'type' => "radio",
    'options' => array(
        'v2' => __('与签名合并', ''),
        'v1' => __('独立成行', '')
    ));

	$options[] = array(
		'name' => __('全屏显示', 'options_framework_theme'),
		'desc' => __('默认开启，勾选关闭', 'options_framework_theme'),
		'id' => 'focus_height',
		'std' => '0',
		'type' => 'checkbox'); 	
	
	 $options[] = array(
		'name' => __('开启视频', 'options_framework_theme'),
		'desc' => __('勾选开启', 'options_framework_theme'),
		'id' => 'focus_amv',
		'std' => '0',
		'type' => 'checkbox');

	 $options[] = array(
		'name' => __('Live', 'options_framework_theme'),
		'desc' => __('勾选开启，视频自动续播，需要开启Pjax功能', 'options_framework_theme'),
		'id' => 'focus_mvlive',
		'std' => '0',
		'type' => 'checkbox');

	 $options[] = array(
		'name' => __('视频地址', 'options_framework_theme'),
		'desc' => __('视频的来源地址，该地址拼接下面的视频名，地址尾部不需要加斜杠', 'options_framework_theme'),
		'id' => 'amv_url',
		'std' => '',
		'type' => 'text');

	 $options[] = array(
		'name' => __('视频名称', 'options_framework_theme'),
		'desc' => __('abc.mp4 ，只需要填写视频文件名 abc 即可，多个用英文逗号隔开如 abc,efg ，无需在意顺序，因为加载是随机的抽取的 ', 'options_framework_theme'),
		'id' => 'amv_title',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('背景图滤镜', 'akina'),
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
		'name' => __('是否开启聚焦', 'options_framework_theme'),
		'desc' => __('默认开启', 'options_framework_theme'),
		'id' => 'top_feature',
		'std' => '1',
		'type' => 'checkbox');	
        
	$options[] = array(
		'name' => __('聚焦样式', 'akina'),
		'id' => 'top_feature_style',
		'std' => "left_and_right",
		'type' => "radio",
		'options' => array(
			'left_and_right' => __('左右交替', ''),
			'bottom_to_top' => __('从下往上', '')
		));    

	$options[] = array(
		'name' => __('聚焦标题', 'options_framework_theme'),
		'desc' => __('默认为聚焦，你也可以修改为其他，当然不能当广告用！不允许！！', 'options_framework_theme'),
		'id' => 'feature_title',
		'std' => '聚焦',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('聚焦图一', 'options_framework_theme'),
		'desc' => __('尺寸257px*160px', 'options_framework_theme'),
		'id' => 'feature1_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图一标题', 'options_framework_theme'),
		'desc' => __('聚焦图一标题', 'options_framework_theme'),
		'id' => 'feature1_title',
		'std' => 'feature1',
		'type' => 'text');	

	$options[] = array(
		'name' => __('聚焦图一描述', 'options_framework_theme'),
		'desc' => __('聚焦图一描述', 'options_framework_theme'),
		'id' => 'feature1_description',
		'std' => 'Description goes here 1',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图一链接', 'options_framework_theme'),
		'desc' => __('聚焦图一链接', 'options_framework_theme'),
		'id' => 'feature1_link',
		'std' => '#',
		'type' => 'text');		
		
	$options[] = array(
		'name' => __('聚焦图二', 'options_framework_theme'),
		'desc' => __('尺寸257px*160px', 'options_framework_theme'),
		'id' => 'feature2_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图二标题', 'options_framework_theme'),
		'desc' => __('聚焦图二标题', 'options_framework_theme'),
		'id' => 'feature2_title',
		'std' => 'feature2',
		'type' => 'text');

	$options[] = array(
		'name' => __('聚焦图二描述', 'options_framework_theme'),
		'desc' => __('聚焦图二描述', 'options_framework_theme'),
		'id' => 'feature2_description',
		'std' => 'Description goes here 2',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图二链接', 'options_framework_theme'),
		'desc' => __('聚焦图二链接', 'options_framework_theme'),
		'id' => 'feature2_link',
		'std' => '#',
		'type' => 'text');
			
	$options[] = array(
		'name' => __('聚焦图三', 'options_framework_theme'),
		'desc' => __('尺寸257px*160px', 'options_framework_theme'),
		'id' => 'feature3_img',
		'std' => $imagepath.'/temp.png',
		'type' => 'upload');

	$options[] = array(
		'name' => __('聚焦图三标题', 'options_framework_theme'),
		'desc' => __('聚焦图三标题', 'options_framework_theme'),
		'id' => 'feature3_title',
		'std' => 'feature3',
		'type' => 'text');	

	$options[] = array(
		'name' => __('聚焦图三描述', 'options_framework_theme'),
		'desc' => __('聚焦图三描述', 'options_framework_theme'),
		'id' => 'feature3_description',
		'std' => 'Description goes here 3',
		'type' => 'text');		
        
	$options[] = array(
		'name' => __('聚焦图三链接', 'options_framework_theme'),
		'desc' => __('聚焦图三链接', 'options_framework_theme'),
		'id' => 'feature3_link',
		'std' => '#',
		'type' => 'text');

		
	//文章页
	$options[] = array(
		'name' => __('文章页', 'options_framework_theme'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('文章点赞', 'akina'),
		'id' => 'post_like',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
		
	$options[] = array(
		'name' => __('文章分享', 'akina'),
		'id' => 'post_share',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
	
	$options[] = array(
		'name' => __('上一篇下一篇', 'akina'),
		'id' => 'post_nepre',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));	
		
	$options[] = array(
		'name' => __('博主信息', 'akina'),
		'id' => 'author_profile',
		'std' => "yes",
		'type' => "radio",
		'options' => array(
			'yes' => __('开启', ''),
			'no' => __('关闭', '')
		));

	$options[] = array(
		'name' => __('支付宝打赏', 'options_framework_theme'),
		'desc' => __('支付宝二维码', 'options_framework_theme'),
		'id' => 'alipay_code',
		'type' => 'upload');

	$options[] = array(
		'name' => __('微信打赏', 'options_framework_theme'),
		'desc' => __('微信二维码', 'options_framework_theme'),
		'id' => 'wechat_code',
		'type' => 'upload');	

		
	//社交选项
	$options[] = array(
		'name' => __('社交网络', 'options_framework_theme'),
		'type' => 'heading');	
	
	$options[] = array(
		'name' => __('微信', 'options_framework_theme'),
		'desc' => __('微信二维码', 'options_framework_theme'),
		'id' => 'wechat',
		'type' => 'upload');
	
    $options[] = array(
		'name' => __('新浪微博', 'options_framework_theme'),
		'desc' => __('新浪微博地址', 'options_framework_theme'),
		'id' => 'sina',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('腾讯QQ', 'options_framework_theme'),
		'desc' => __('tencent://message/?uin={{QQ号码}}，如tencent://message/?uin=123456', 'options_framework_theme'),
		'id' => 'qq',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Telegram', 'options_framework_theme'),
		'desc' => __('Telegram链接', 'options_framework_theme'),
		'id' => 'telegram',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('QQ空间', 'options_framework_theme'),
		'desc' => __('QQ空间地址', 'options_framework_theme'),
		'id' => 'qzone',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('GitHub', 'options_framework_theme'),
		'desc' => __('GitHub地址', 'options_framework_theme'),
		'id' => 'github',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Lofter', 'options_framework_theme'),
		'desc' => __('lofter地址', 'options_framework_theme'),
		'id' => 'lofter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('BiliBili', 'options_framework_theme'),
		'desc' => __('B站地址', 'options_framework_theme'),
		'id' => 'bili',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('优酷视频', 'options_framework_theme'),
		'desc' => __('优酷地址', 'options_framework_theme'),
		'id' => 'youku',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('网易云音乐', 'options_framework_theme'),
		'desc' => __('网易云音乐地址', 'options_framework_theme'),
		'id' => 'wangyiyun',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Twitter', 'options_framework_theme'),
		'desc' => __('推特地址', 'options_framework_theme'),
		'id' => 'twitter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Facebook', 'options_framework_theme'),
		'desc' => __('脸书地址', 'options_framework_theme'),
		'id' => 'facebook',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google+', 'options_framework_theme'),
		'desc' => __('G+地址', 'options_framework_theme'),
		'id' => 'googleplus',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('简书', 'options_framework_theme'),
		'desc' => __('简书地址', 'options_framework_theme'),
		'id' => 'jianshu',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('CSDN', 'options_framework_theme'),
		'desc' => __('CSND社区地址', 'options_framework_theme'),
		'id' => 'csdn',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('知乎', 'options_framework_theme'),
		'desc' => __('知乎地址', 'options_framework_theme'),
		'id' => 'zhihu',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('邮箱-用户名', 'options_framework_theme'),
		'desc' => __('name@domain.com 的 name 部分，前端仅具有js运行环境时才能获取完整地址，可放心填写', 'options_framework_theme'),
		'id' => 'email_name',
		'std' => '',
		'type' => 'text');

    $options[] = array(
		'name' => __('邮箱-域名', 'options_framework_theme'),
		'desc' => __('name@domain.com 的 domain.com 部分', 'options_framework_theme'),
		'id' => 'email_domain',
		'std' => '',
		'type' => 'text');	

	//前台登录
	$options[] = array(
		'name' => __('前台登录', 'options_framework_theme'),
		'type' => 'heading' );

	$options[] = array(
		'name' => __('指定登录地址', 'options_framework_theme'),
		'desc' => __('强制不使用后台地址登陆，填写新建的登陆页面地址，比如 http://www.xxx.com/login【注意】填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况', 'options_framework_theme'),
		'id' => 'exlogin_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('指定注册地址', 'options_framework_theme'),
		'desc' => __('该链接使用在登录页面作为注册入口，建议填写', 'options_framework_theme'),
		'id' => 'exregister_url',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('允许用户注册', 'options_framework_theme'),
		'desc' => __('勾选开启，允许用户在前台注册', 'options_framework_theme'),
		'id' => 'ex_register_open',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('登录后自动跳转', 'options_framework_theme'),
		'desc' => __('勾选开启，管理员跳转至后台，用户跳转至主页', 'options_framework_theme'),
		'id' => 'login_urlskip',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('注册验证（仅前端，后端强制开启）', 'options_framework_theme'),
		'desc' => __('勾选开启滑动验证', 'options_framework_theme'),
		'id' => 'login_validate',
		'std' => '0',
		'type' => 'checkbox');	

    //CDN 优化
	$options[] = array(
		'name' => __('CDN', 'options_framework_theme'),
		'type' => 'heading' );
        
	$options[] = array(
		'name' => __('图片库 CDN', 'options_framework_theme'),
		'desc' => __('注意：填写格式为 http://你的CDN域名/20xx/xx/xx.png。<br>也就是说，原路径为 http://your.domain/wp-content/uploads/2018/05/xx.png 的图片将从 http://你的CDN域名/2018/05/xx.png 加载', 'options_framework_theme'),
		'id' => 'qiniu_cdn',
		'std' => '',
		'type' => 'text');  

    $options[] = array(
		'name' => __('Adobe Typekit ID 1', 'options_framework_theme'),
		'desc' => __('加载 Adobe 字体，填写的是 js 文件名，请把<a href="https://typekit.com/fonts/source-han-serif-simplified-chinese">这页</a>七个字体都加入到你的 kit。免费账号有每月 2,5000 PV 的使用限制，可注册多个ID，每次随机选择一个调用，如果访问量没那么高，那么填这里第一个就OK了', 'options_framework_theme'),
		'id' => 'adobe_id_1',
		'std' => '',
		'type' => 'text'); 

	$options[] = array(
		'name' => __('Adobe Typekit ID 2', 'options_framework_theme'),
		'desc' => __('可留空，如果仅填前两个ID，那么随机到此 ID 的概率是1/3，随机到 ID 1 的概率是2/3', 'options_framework_theme'),
		'id' => 'adobe_id_2',
		'std' => '',
		'type' => 'text'); 

	$options[] = array(
		'name' => __('Adobe Typekit ID 3', 'options_framework_theme'),
		'desc' => __('可留空，如果三个都填写，那么三个 ID 随机调用，概率各为 1/3', 'options_framework_theme'),
		'id' => 'adobe_id_3',
		'std' => '',
		'type' => 'text'); 
        
    $options[] = array(
		'name' => __('开启 jsDelivr 测试？', 'options_framework_theme'),
		'desc' => __('如不清楚什么意思切勿勾选！', 'options_framework_theme'),
		'id' => 'jsdelivr_cdn_test',
		'std' => '0',
		'type' => 'checkbox');

    $options[] = array(
		'name' => __('jsDelivr 版本号', 'options_framework_theme'),
		'desc' => __('默认值为3.4.5', 'options_framework_theme'),
		'id' => 'jsdelivr_cdn_version',
		'std' => '3.4.5',
		'type' => 'text');  
        
    	//其他
	$options[] = array(
		'name' => __('其他', 'options_framework_theme'),
		'type' => 'heading' );
        
    $options[] = array(
    'name' => __('关于', 'options_framework_theme'),
    'desc' => __('Theme Sakura v3.0.6  |  <a href="https://2heng.xin/theme-sakura/">主题说明</a>  |  <a href="https://github.com/mashirozx/Sakura/">源码</a>', 'options_framework_theme'),
    'id' => 'theme_intro',
    'std' => '',
    'type' => 'typography ');

	$options[] = array(
		'name' => __('页脚悬浮播放器', 'options_framework_theme'),
		'desc' => __('如果不需要播放器留空即可。填写网易云音乐的「歌单」ID，eg：https://music.163.com/#/playlist?id=2288037900的ID是2288037900', 'options_framework_theme'),
		'id' => 'playlist_id',
		'std' => '2288037900',
		'type' => 'text');
        
	$options[] = array(
		'name' => __('Cookie 版本控制', 'options_framework_theme'),
		'desc' => __('用于更新前端 cookie，可使用任意字符串，比如日期：---2018/5/16', 'options_framework_theme'),
		'id' => 'cookie_version',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('开启PJAX局部刷新（建议开启）', 'options_framework_theme'),
		'desc' => __('原理与Ajax相同', 'options_framework_theme'),
		'id' => 'poi_pjax',
		'std' => '0',
		'type' => 'checkbox');
    
    $options[] = array(
		'name' => __('开启NProgress加载进度条', 'options_framework_theme'),
		'desc' => __('默认不开启，勾选开启', 'options_framework_theme'),
		'id' => 'nprogress_on',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('开启公告', 'options_framework_theme'),
		'desc' => __('默认不显示，勾选开启', 'options_framework_theme'),
		'id' => 'head_notice',
		'std' => '0',
		'type' => 'checkbox');	

	$options[] = array(
		'name' => __('公告内容', 'options_framework_theme'),
		'desc' => __('公告内容，文字超出142个字节将会被滚动显示（移动端无效），一个汉字 = 3字节，一个字母 = 1字节，自己计算吧', 'options_framework_theme'),
		'id' => 'notice_title',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('首页不显示的分类文章', 'options_framework_theme'),
		'desc' => __('填写分类ID，多个用英文“ , ”分开', 'options_framework_theme'),
		'id' => 'classify_display',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'name' => __('图片展示分类', 'options_framework_theme'),
		'desc' => __('填写分类ID，多个用英文“ , ”分开', 'options_framework_theme'),
		'id' => 'image_category',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('发件地址前缀', 'options_framework_theme'),
		'desc' => __('用于发送系统邮件，在用户的邮箱中显示的发件人地址，不要使用中文，默认系统邮件地址为 poi@你的域名.com', 'options_framework_theme'),
		'id' => 'mail_user_name',
		'std' => 'poi',
		'type' => 'text');

	$options[] = array(
		'name' => __('允许私密评论', 'options_framework_theme'),
		'desc' => __('允许用户设置自己的评论对其他人不可见', 'options_framework_theme'),
		'id' => 'open_private_message',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('机器人验证', 'options_framework_theme'),
		'desc' => __('开启机器人验证', 'options_framework_theme'),
		'id' => 'norobot',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('评论UA信息', 'options_framework_theme'),
		'desc' => __('勾选开启，用户的浏览器，操作系统信息', 'options_framework_theme'),
		'id' => 'open_useragent',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('开启多说插件支持', 'options_framework_theme'),
		'desc' => __('如果使用多说插件，请勾选此项', 'options_framework_theme'),
		'id' => 'general_disqus_plugin_support',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('后台登陆界面背景图', 'options_framework_theme'),
		'desc' => __('该地址为空则使用默认图片', 'options_framework_theme'),
		'id' => 'login_bg',
		'type' => 'upload');
        
	return $options;
}