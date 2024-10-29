<?php
/*
Plugin Name: Advanced Iranian Widget
Plugin URI: http://Technopolis.ir/
Description:  Advanced Iranian Widget is created by <a href="http://tohid.ir.tc/" target="_blank">Towhid Nategheian</a> and suppoted at <a href="http://Technopolis.ir" target="_blank">Technopolis Web Developers Group</a>.
Version: 1.1
Author: Towhid Nategheian
Author URI: http://tohid.ir.tc/
*/

// This plugin is under GPLv2 License, Iranian User detector under Creative Common Licesed bye Towhid Nategheian[this plugins author]
//Full support @ technopolis.ir
//for English support please mail me at info@technopolis.ir

/*

جامیست که عقل آفرین می زندش
صد بوسه ز مهر بر جبین می زندش
این کوزه گر دهر چنین جام لطیف
می سازد و باز بر زمین می زندش

Tis a goblet that earns the Mind's praise;
One hundred kisses upon its forehead man pays.
This potter of Fate makes his delicate pot so,
And alas! from his hand to the ground it strays.

Khayyám's Rubáiyát

*/

class AdvancedIranianWidget extends WP_Widget {

    function AdvancedIranianWidget() { //مشخصات ویدگت
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Advanced Iranian Widget By Technopolis'));
        $control_ops = array('width' => 400, 'height' => 350);
        $this->WP_Widget('AdvancedIranianWidget', __('Advanced Iranian Widget'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
	echo "<!--Advanced Iranian Widget - content start-->";
	$OnlyShowToIranians = $instance['OnlyShowToIranians']; // محتوا را فقط به ایرانی ها نشان بده
	$showwidget=true;
	switch ($OnlyShowToIranians) {
		case "1":
			if (!IsIranian()){ return 0;}// اگر ایرانی نیست خارج شو
		break;
		case "2":
			if (IsIranian()){ return 0;}// اگر ایرانی است خارج شو
		break;
	}
	//خروجی ویدگت در تم
	extract($args);
	$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);//عنوان
	$titleUrl = apply_filters('widget_title', empty($instance['titleUrl']) ? '' : $instance['titleUrl'], $instance);//آدرس
	$newWindow = $instance['newWindow'] ? '1' : '0';//باز کردن آدرس در پنجره ی جدید
	$text = apply_filters( 'widget_text', $instance['text'], $instance );// محتوی
	echo $before_widget; 
	if( $titleUrl && $title )
		$title = '<a href="'.$titleUrl.'"'.($newWindow == '1'?' target="_blank"':'').' title="'.$title.'">'.$title.'</a>';
	$OnlyShowToIranians == '1'?add_option("OnlyShowToIranians", "1"):add_option("OnlyShowToIranians", "0");
	if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
				<div class="textwidget"><?php if($instance['filter']) { ob_start(); eval("?>$text<?php "); $output = ob_get_contents(); ob_end_clean(); echo wpautop($output); } else eval("?>".$text."<?php "); ?></div>
	<?php
	
	echo $after_widget;
	echo "<!--Advanced Iranian Widget - content End-->";
}
    
    function update( $new_instance, $old_instance ) { // ذخیره تنظیمات ویدگت
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['titleUrl'] = GetValidURL(strip_tags($new_instance['titleUrl']));
        $instance['newWindow'] = $new_instance['newWindow'] ? 1 : 0;
        
		$instance['OnlyShowToIranians'] = $new_instance['OnlyShowToIranians'];
		
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = wp_filter_post_kses( $new_instance['text'] );
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form( $instance ) {//ویدگت در بخش ابزارک ها 
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'titleUrl' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
        $titleUrl = strip_tags($instance['titleUrl']);
        $newWindow = $instance['newWindow'] ? 'checked="checked"' : '';
        $OnlyShowToIranians = $instance['OnlyShowToIranians'];
		$text = format_to_edit($instance['text']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: ') ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('titleUrl'); ?>"><?php _e('URL adress:') ?></label>
        <input class="widefat" style="direction:ltr;" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo esc_attr($titleUrl); ?>" /></p>
        <p><input class="checkbox" type="checkbox" <?php echo $newWindow; ?> id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" />
        <label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('Open link in new window') ?></label></p>
       
        
        <p><select class="widefat" <?php echo $OnlyShowToIranians; ?> id="<?php echo $this->get_field_id('OnlyShowToIranians'); ?>" name="<?php echo $this->get_field_name('OnlyShowToIranians'); ?>" >
        <optgroup label="<?php _e('Public') ?>">
        <option value="0" <?php if ($OnlyShowToIranians==0) {print("selected");} ?>><?php _e('Show this widget to every one') ?></option>
        </optgroup>
        <optgroup label="<?php _e('Private') ?>">
        <option value="1" <?php if ($OnlyShowToIranians==1) {print("selected");} ?>><?php _e('Only show to Iranians') ?></option>
        <option value="2" <?php if ($OnlyShowToIranians==2) {print("selected");} ?>><?php _e('Hide from Iranians') ?></option>
        </optgroup>
        </select>
        </p>
       
       
        <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('you have PHP scripts,HTML, Javascript, Flash, no problem!</br> Here enter anything you wish:') ?></label>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

        <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Put this inside a paragraph') ?></label></p>
<!--فوق ویدگت تکنوپلیس-->

<?php
    }
}
/**
 * شروع کار ویدگت
 *
 * @ ن 1.0
 */
function AdvancedIranianWidgetInit() {
    register_widget('AdvancedIranianWidget');//ثبت ویدگت
}
add_action('widgets_init', 'AdvancedIranianWidgetInit');
/**
 * شروع کار پلاگین 
 *
 * @ ن 1.0
 */
function AdvancedIranianWidgetPluginInit() {
	if (function_exists('load_plugin_textdomain'))
		load_plugin_textdomain( 'AdvancedIranianWidget', 'wp-content/plugins/Advanced-Iranian-Widget/languages' );
}
add_action( 'init', 'AdvancedIranianWidgetPluginInit' );
/**
 * دریافت نسخه پلاگین
 * @ ن 1.0
 */
function AdvancedIranianWidgetVersion() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Version'];
}
/**
 * دریافت نام پلاگین
 * @ ن 1.0
 */
function AdvancedIranianWidgetName() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Name'];
}
/**
 * دریافت آدرس URI پلاگین
 * @ ن 1.0
 */
function AdvancedIranianWidgetURI() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['PluginURI'];
}
/**
 * تبدیل آدرس به فرم صحیح آن
 *
 * @ ن 1.0
 */
function GetValidURL($titleurladress) {
	if (!$titleurladress) return;
	$titleurladress = str_replace("http://","",$titleurladress);
	$titleurladress = "http://$titleurladress";
	return $titleurladress;
}
/**
 * آیا بازدیدکننده ایرانیسیت
 *
 * @ ن 1.0
 */
function IsIranian() {// This function checks to see if visitor is iranian, This function is under Creative Common License for "Towhid Nategheian"
$ips[1] = array (42991616,45088768,520568832,520585216,521717760,521725952,521754624,521758720,521764864,521791488,522002432,522715136,523182080,523763712,528658432,531247104,531355648,532185088,532770816,532779008,534368256,772995072,773148672,773605376,773849088,774004736,774021120,774135808,774275072,778305536,781123584,781459456,782516224,783792128,785448960,786432000,787808256,788013056,788094976,788242432,788518912,1044152320,1046904832,1052835840,1054629888,1294237696,1296908288,1298677760,1307394048,1307418624,1307959296,1311113216,1315815424,1315860480,1315897344,1318723584,1318920192,1319018496,1333723136,1334099968,1336578048,1336901632,1346547712,1346760704,1346859008,1347092480,1354694656,1358036992,1358790656,1358794752,1359740928,1360797696,1360801792,1360916480,1361027072,1361031168,1361043456,1364889600,1364951040,1364955136,1369636864,1382268928,1383268352,1402191872,1412415488,1425080320,1426669568,1427046400,1434812416,1438187520,1439039488,1446576128,1449992192,1466630144,1475137536,1475846144,1475903488,1485250560,1502642176,1503985664,1505280000,1507676160,1508589568,1533149184,1538801664,1538965504,1540258304,1540327936,1540400384,1540485632,1540624384,1540625152,1540684800,1540883456,1540964352,1541164800,1541325824,1541434880,1541451776,1541485568,1541595136,1541717248,1541789184,1541808128,1541947392,1541948416,1541949440,1542010880,1546268672,1546780672,1547546624,1547612160,1559412736,1567490048,1572739072,1581940736,1583710208,1583722496,1583738880,1586208768,1588985856,1589116928,1589149696,1592305664,1592885248,1596325888,1599111168,1599160320,1599209472,1599225856,1602369536,1602416640,1603198976,1833484288,1833623552,1834956800,1834958848,1835868160,1835966464,1835999232,1836761088,1836941312,1839366144,1841889280,1842061312,1843494912,1843806208,1844359168,1844379648,2151784448,2156658688,2193180672,2197798912,2453831680,2654648320,2668912640,2684297216,2765563904,2765568000,2765586432,2953592832,2955837440,2956496896,2956890112,2957197312,2959417344,2959421440,2959532032,2967277568,2967289856,2987730944,2987761664,2987804672,2994929664,2996633600,2997714944,3000434688,3000754176,3001819136,3001991168,3002044416,3002607616,3002847232,3002892288,3002925056,3156344832,3159048192,3160227840,3161866240,3162071040,3162079232,3162406912,3163062272,3164471296,3166679040,3170172928,3170697216,3238562560,3239884032,3244824064,3244872704,3244884480,3244885504,3244999680,3250420224,3258770432,3264386048,3269525504,3272902656,3277372416,3278775808,3281133568,3282739968,3284093440,3285396480,3287631360,3556884480,3557834752,3558981632,3560103936,3562012672,3562422272,3563028480,3564683264,3583213568,3585081344,3585089536,3585097728,3586326528,3587162112,3587776512,3588857856,3641380864,3642265600,3642306560,3644887040,3645030400,3645034496,3650277376,3651858432,3651952640,3652063232,3654942720);
$ips[2] = array (43253759,46137343,520585215,520589311,521719807,521727999,521756671,521760767,521766911,521793535,522010623,522717183,523190271,524025855,528662527,531251199,531357695,532201471,532772863,532783103,534370303,772997119,773152767,773607423,773857279,774012927,774029311,774143999,774283263,778371071,781189119,781463551,782532607,783794175,785514495,786563071,787841023,788021247,788103167,788250623,788520959,1044185087,1046908927,1052844031,1054638079,1294270463,1296924671,1298694143,1307402239,1307426815,1307963391,1311244287,1315819519,1315864575,1315901439,1318731775,1318928383,1319026687,1333755903,1334108159,1336580095,1336918015,1346551807,1346764799,1346863103,1347096575,1354760191,1358041087,1358794751,1358798847,1359773695,1360801791,1360805887,1360920575,1361031167,1361035263,1361051647,1364893695,1364955135,1364959231,1369638911,1382285311,1383276543,1402208255,1412431871,1425096703,1426685951,1427062783,1434845183,1438253055,1439055871,1446608895,1450000383,1466695679,1475139583,1475854335,1475911679,1485254655,1502658559,1504018431,1505288191,1507680255,1508605951,1533280255,1538809855,1538973695,1540258815,1540328447,1540400639,1540485887,1540624639,1540625407,1540685055,1540883711,1540964863,1541165055,1541326847,1541435391,1541452287,1541486591,1541595647,1541717503,1541789695,1541808383,1541948415,1541948927,1541950463,1542011903,1546270719,1546797055,1547550719,1547616255,1559420927,1567555583,1572741119,1581957119,1583714303,1583726591,1583742975,1586216959,1589116927,1589149695,1589182463,1592307711,1592901631,1596391423,1599127551,1599176703,1599225855,1599242239,1602371583,1602418687,1603203071,1833488383,1833627647,1834958847,1834960895,1835876351,1835974655,1836007423,1836777471,1836957695,1839398911,1841897471,1842069503,1843511295,1843822591,1844363263,1844383743,2151792639,2156691455,2193182719,2197815295,2453833727,2654650367,2668916735,2684299263,2765565951,2765570047,2765619199,2953596927,2955845631,2956500991,2956892159,2957201407,2959421439,2959423487,2959540223,2967281663,2967291903,2987732991,2987763711,2987806719,2994995199,2996649983,2997747711,3000451071,3000758271,3001823231,3001995263,3002048511,3002609663,3002849279,3002908671,3002941439,3156410367,3159064575,3160229887,3161882623,3162079231,3162087423,3162415103,3163095039,3164602367,3166681087,3170238463,3170729983,3238562815,3239884287,3244824319,3244872959,3244884735,3244885759,3245000703,3250420735,3258771455,3264387071,3269591039,3272902911,3277372927,3278776319,3281141759,3282740223,3284093951,3285397503,3287631871,3556886527,3557842943,3558989823,3560112127,3562020863,3562430463,3563036671,3564691455,3583221759,3585089535,3585097727,3585114111,3586342911,3587178495,3587784703,3588866047,3641384959,3642269695,3642310655,3644891135,3645034495,3645038591,3650281471,3651862527,3651960831,3652067327,3655073791);
if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))$TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];else $TheIp=$_SERVER['REMOTE_ADDR'];
$TheIp=ip2long(trim($TheIp));
$count = count($ips[1]);$found=false;
for ($i = 0; $i <= $count; $i++) {
    if ($TheIp>=$ips[1][$i]){
        if  ($TheIp<=$ips[2][$i]){
            $found=true;
            break;
        }
    }else if($TheIp<$ips[1][$i]){$found=false;break;}
}
unset($ips);
	return $found;//در صورت ایرانی بودن مقدار صحیح بر میگرداند
}
 
/**
 * ایجاد نوار آپدیت در داشبرد
 * @ن 1.0
 */
function AdvancedIranianWidgetUpdateBar() {// update notification for admin

    if ( !current_user_can('update_plugins') ) return false; //بررسی مجوز کاربر برای افزایش امنیت
    if ( stristr(trim($info->version), trim(AdvancedIranianWidgetVersion())) ) return false; //بررسی بروز بودن پلاگین

    $slug = "Advanced-Iranian-Widget";//محل پوشه و فایل هسته : خیلی مهم
    $file = "$slug/$slug.php";
    
    if(!function_exists('plugins_api'))
        include(ABSPATH . "wp-admin/includes/plugin-install.php");
    $info = plugins_api('plugin_information', array('slug' => $slug ));
    
    $plugin_name = AdvancedIranianWidgetName();
    $plugin_url = AdvancedIranianWidgetURI();
    if(function_exists('self_admin_url')) { //برای سازگاری با بعضی نسخه های وردپرس
        $update_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
    }
    else {// در بعضی نسخه ها محل کتابخانه بروزرسانی متفاوت است
        $update_url = wp_nonce_url( get_bloginfo('wpurl')."/wp-admin/".('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
    }

    echo '<div id="update-nag">';
    printf(__('A new version of ').'<a href="%s" target="_blank">%s</a>'.__(' is awailable. Please ').' <a href="%s">'.__('Upgdate').'</a>'	.__(' to newer version.'),$plugin_url, $plugin_name, $update_url );

	echo '</br>'.__('Thank you for using '). $plugin_name . __(' from'). ' <a href="http://Technopolis.ir/">'.__('Technopolis Web Designers Group').'</a>.'.__(' Cheers!');
    echo '</div>';
}
add_action('admin_notices', 'AdvancedIranianWidgetUpdateBar');


?>