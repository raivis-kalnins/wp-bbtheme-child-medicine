<?php
defined( 'ABSPATH' ) || exit;

function wpbb_medicine_project_mode( $mode ) { return 'medicine'; }
add_filter( 'wp_theme_project_mode', 'wpbb_medicine_project_mode' );

function wpbb_medicine_assets() {
    $theme = wp_get_theme();
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if ( ! is_readable( $manifest ) ) return;
    $data = json_decode( (string) file_get_contents( $manifest ), true );
    if ( ! is_array( $data ) ) return;
    if ( ! empty( $data['src/scss/public.scss']['file'] ) ) {
        wp_enqueue_style( 'wpbb-medicine-app', get_stylesheet_directory_uri() . '/dist/' . ltrim( $data['src/scss/public.scss']['file'], '/' ), array(), $theme->get( 'Version' ) );
        if ( function_exists( 'wp_theme_sector_customizer_css' ) ) wp_add_inline_style( 'wpbb-medicine-app', wp_theme_sector_customizer_css( '#176b87', '16px', '--sector-primary', '--sector-radius' ) );
    }
    if ( ! empty( $data['src/js/main.js']['file'] ) ) wp_enqueue_script( 'wpbb-medicine-app', get_stylesheet_directory_uri() . '/dist/' . ltrim( $data['src/js/main.js']['file'], '/' ), array(), $theme->get( 'Version' ), true );
    if ( wpbb_medicine_needs_directory_assets() ) {
        $file = get_stylesheet_directory() . '/assets/js/doctor-directory.js';
        wp_enqueue_script( 'wpbb-medicine-directory', get_stylesheet_directory_uri() . '/assets/js/doctor-directory.js', array(), filemtime( $file ), true );
        wp_localize_script( 'wpbb-medicine-directory', 'WPBBMedicineDirectory', array( 'ajaxUrl'=>admin_url('admin-ajax.php'), 'nonce'=>wp_create_nonce('wpbb_medicine_doctors') ) );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_medicine_assets', 30 );

function wpbb_medicine_needs_directory_assets() {
    if ( is_post_type_archive( 'doctor' ) || is_tax( array( 'doctor_speciality', 'doctor_location' ) ) ) return true;
    if ( is_singular() ) {
        $content = (string) get_post_field( 'post_content', get_queried_object_id() );
        return has_shortcode( $content, 'wp_theme_doctor_directory' ) || has_block( 'wpbb/sector-finder', $content );
    }
    return false;
}

function wpbb_medicine_register_content() {
    register_post_type( 'doctor', array(
        'labels'=>array('name'=>__('Doctors','wp-bbtheme-child-medicine'),'singular_name'=>__('Doctor','wp-bbtheme-child-medicine'),'add_new_item'=>__('Add doctor','wp-bbtheme-child-medicine')),
        'public'=>true,'show_in_rest'=>true,'has_archive'=>'doctors','rewrite'=>array('slug'=>'doctors'),'menu_icon'=>'dashicons-businessperson','supports'=>array('title','editor','excerpt','thumbnail','page-attributes')
    ) );
    register_taxonomy( 'doctor_speciality', 'doctor', array('label'=>__('Specialities','wp-bbtheme-child-medicine'),'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>array('slug'=>'speciality')) );
    register_taxonomy( 'doctor_location', 'doctor', array('label'=>__('Locations','wp-bbtheme-child-medicine'),'public'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>array('slug'=>'clinic-location')) );
}
add_action( 'init', 'wpbb_medicine_register_content' );

function wpbb_medicine_doctor_fields() {
    return array('credentials'=>__('Credentials','wp-bbtheme-child-medicine'),'experience'=>__('Years of experience','wp-bbtheme-child-medicine'),'consultation_price'=>__('Consultation price','wp-bbtheme-child-medicine'),'languages'=>__('Languages','wp-bbtheme-child-medicine'),'availability'=>__('Availability note','wp-bbtheme-child-medicine'));
}
function wpbb_medicine_doctor_box() { add_meta_box('wpbb-doctor-details',__('Doctor details','wp-bbtheme-child-medicine'),'wpbb_medicine_doctor_box_render','doctor','normal','high'); }
add_action('add_meta_boxes','wpbb_medicine_doctor_box');
function wpbb_medicine_doctor_box_render($post) {
    wp_nonce_field('wpbb_medicine_save_doctor','wpbb_medicine_doctor_nonce');
    echo '<div class="wpbb-doctor-admin" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px">';
    foreach(wpbb_medicine_doctor_fields() as $key=>$label){$value=get_post_meta($post->ID,'_doctor_'.$key,true);echo '<label><strong>'.esc_html($label).'</strong><input class="widefat" type="text" name="wpbb_doctor['.esc_attr($key).']" value="'.esc_attr($value).'"></label>';}
    echo '</div>';
}
function wpbb_medicine_save_doctor($post_id){
    if(empty($_POST['wpbb_medicine_doctor_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wpbb_medicine_doctor_nonce'])),'wpbb_medicine_save_doctor')||!current_user_can('edit_post',$post_id))return;
    $values=isset($_POST['wpbb_doctor'])&&is_array($_POST['wpbb_doctor'])?wp_unslash($_POST['wpbb_doctor']):array();
    foreach(wpbb_medicine_doctor_fields() as $key=>$label)update_post_meta($post_id,'_doctor_'.$key,sanitize_text_field($values[$key]??''));
}
add_action('save_post_doctor','wpbb_medicine_save_doctor');

function wpbb_medicine_demo_profile( $profile ) {
    $assets = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/doctors/';
    $profile['id']='medicine'; $profile['name']=__('Medical Practice','wp-bbtheme-child-medicine'); $profile['commerce']=false;
    $profile['services_eyebrow']=__('Clinical services','wp-bbtheme-child-medicine'); $profile['services_heading']=__('Specialist care explained clearly, from first appointment onward.','wp-bbtheme-child-medicine');
    $profile['about_eyebrow']=__('Patient-first care','wp-bbtheme-child-medicine'); $profile['industries_eyebrow']=__('Ways we can help','wp-bbtheme-child-medicine'); $profile['industries_heading']=__('Care pathways for everyday concerns, specialist review and ongoing health.','wp-bbtheme-child-medicine');
    $profile['process_eyebrow']=__('Your appointment','wp-bbtheme-child-medicine'); $profile['process_heading']=__('Find a clinician, book a time and arrive knowing what happens next.','wp-bbtheme-child-medicine'); $profile['faq_heading']=__('Appointment and patient questions, answered before you book.','wp-bbtheme-child-medicine');
    $profile['eyebrow']=__('Care that starts with finding the right clinician','wp-bbtheme-child-medicine');
    $profile['hero_title']=__('Specialist care, easier to find and book.','wp-bbtheme-child-medicine');
    $profile['hero_text']=__('Search trusted clinicians by speciality and location, compare their experience, then request a convenient appointment without leaving the site.','wp-bbtheme-child-medicine');
    $profile['hero_image']=$assets.'amelia-hart.svg'; $profile['about_image']=$assets.'daniel-lee.svg';
    $profile['primary_label']=__('Find a doctor','wp-bbtheme-child-medicine'); $profile['primary_url']=get_post_type_archive_link('doctor')?:home_url('/doctors/');
    $profile['secondary_label']=__('Book an appointment','wp-bbtheme-child-medicine'); $profile['secondary_url']=home_url('/appointments/');
    $profile['hero_slides']=array(
      array('type'=>'hero','eyebrow'=>__('Trusted clinical team','wp-bbtheme-child-medicine'),'title'=>__('Specialist care, easier to find and book.','wp-bbtheme-child-medicine'),'text'=>$profile['hero_text'],'image'=>$assets.'amelia-hart.svg','buttonText'=>__('Find a doctor','wp-bbtheme-child-medicine'),'buttonUrl'=>$profile['primary_url'],'secondaryText'=>__('Book appointment','wp-bbtheme-child-medicine'),'secondaryUrl'=>$profile['secondary_url']),
      array('type'=>'hero','eyebrow'=>__('Same-week appointments','wp-bbtheme-child-medicine'),'title'=>__('Speak to the right specialist sooner.','wp-bbtheme-child-medicine'),'text'=>__('Browse clinical profiles, languages, locations and availability before choosing an appointment.','wp-bbtheme-child-medicine'),'image'=>$assets.'maija-ozola.svg','buttonText'=>__('Meet the team','wp-bbtheme-child-medicine'),'buttonUrl'=>$profile['primary_url'],'secondaryText'=>__('How it works','wp-bbtheme-child-medicine'),'secondaryUrl'=>'#services')
    );
    $profile['services']=array(
      array(__('General medicine','wp-bbtheme-child-medicine'),__('Everyday health concerns, prevention and onward specialist referrals.','wp-bbtheme-child-medicine')),
      array(__('Cardiology','wp-bbtheme-child-medicine'),__('Assessment, diagnostics and ongoing heart-health support.','wp-bbtheme-child-medicine')),
      array(__('Dermatology','wp-bbtheme-child-medicine'),__('Skin, hair and nail consultations with clear treatment pathways.','wp-bbtheme-child-medicine')),
      array(__('Paediatrics','wp-bbtheme-child-medicine'),__('Calm, family-centred care for children and young people.','wp-bbtheme-child-medicine'))
    );
    $profile['industries']=array(
      array(__('In-clinic consultation','wp-bbtheme-child-medicine'),__('Meet your clinician at the most convenient clinic location.','wp-bbtheme-child-medicine')),
      array(__('Video consultation','wp-bbtheme-child-medicine'),__('Secure remote appointments when an in-person visit is not necessary.','wp-bbtheme-child-medicine')),
      array(__('Diagnostics','wp-bbtheme-child-medicine'),__('Coordinate tests and follow-up appointments from the same care pathway.','wp-bbtheme-child-medicine')),
      array(__('Preventive health','wp-bbtheme-child-medicine'),__('Screening, check-ups and practical plans for long-term wellbeing.','wp-bbtheme-child-medicine'))
    );
    $profile['stats']=array(array('24+',__('Clinical specialists','wp-bbtheme-child-medicine')),array('8',__('Core specialities','wp-bbtheme-child-medicine')),array('4.9',__('Patient rating','wp-bbtheme-child-medicine')),array('<24h',__('Typical response','wp-bbtheme-child-medicine')));
    $profile['process']=array(array('01',__('Find','wp-bbtheme-child-medicine'),__('Filter by speciality, location and the clinician who fits your needs.','wp-bbtheme-child-medicine')),array('02',__('Book','wp-bbtheme-child-medicine'),__('Choose a service, date and available time in the appointment block.','wp-bbtheme-child-medicine')),array('03',__('Attend','wp-bbtheme-child-medicine'),__('Receive confirmation and arrive with the right clinical team already selected.','wp-bbtheme-child-medicine')));
    $profile['about_title']=__('Human clinical expertise with a clearer digital front door.','wp-bbtheme-child-medicine');
    $profile['about_text']=__('Doctor profiles, specialities, fast discovery and straightforward appointment requests in one calm patient experience.','wp-bbtheme-child-medicine');
    $profile['cta_title']=__('Need help choosing the right specialist?','wp-bbtheme-child-medicine'); $profile['cta_text']=__('Contact the care team or start with the doctor directory and book a suitable appointment.','wp-bbtheme-child-medicine');
    $profile['footer_text']=__('A modern medical directory for specialist discovery, patient information and appointment requests.','wp-bbtheme-child-medicine');
    $profile['page_labels']=array('about'=>__('About the clinic','wp-bbtheme-child-medicine'),'services'=>__('Specialities','wp-bbtheme-child-medicine'),'industries'=>__('Patient services','wp-bbtheme-child-medicine'),'contact'=>__('Contact','wp-bbtheme-child-medicine'),'blog'=>__('Health insights','wp-bbtheme-child-medicine'));
    $profile['palette']=array('theme_brand_color'=>'#176b87','theme_accent_color'=>'#57b8a6','theme_text_color'=>'#15263a','theme_heading_color'=>'#102238','theme_background_color'=>'#ffffff','theme_surface_color'=>'#ffffff','theme_surface_alt_color'=>'#f2f8f8','theme_border_color'=>'#dbe8ea','theme_link_color'=>'#176b87','theme_link_hover_color'=>'#0d4f68','theme_radius'=>'16px','theme_font_provider'=>'system');
    return $profile;
}
add_filter('wp_theme_demo_profile','wpbb_medicine_demo_profile');

function wpbb_medicine_demo_image($slug,$title){
    $existing=get_page_by_path('demo-doctor-'.$slug,OBJECT,'attachment'); if($existing)return $existing->ID;
    $source=get_stylesheet_directory().'/assets/img/doctors/'.$slug.'.svg'; if(!is_readable($source))return 0;
    $uploads=wp_upload_dir(); $dir=trailingslashit($uploads['basedir']).'wpbb-medicine-demo'; wp_mkdir_p($dir); $target=$dir.'/'.$slug.'.svg'; if(!file_exists($target))copy($source,$target);
    $id=wp_insert_attachment(array('post_mime_type'=>'image/svg+xml','post_title'=>$title,'post_name'=>'demo-doctor-'.$slug,'post_status'=>'inherit'),$target); if($id&&!is_wp_error($id))update_post_meta($id,'_wp_attachment_image_alt',$title); return is_wp_error($id)?0:$id;
}
function wpbb_medicine_seed_doctors(){
    $specialities=array('General Medicine','Cardiology','Dermatology','Paediatrics','Neurology','Physiotherapy'); foreach($specialities as $name) if(!term_exists($name,'doctor_speciality'))wp_insert_term($name,'doctor_speciality');
    $locations=array('Central Clinic','Riverside Clinic','North Medical Centre'); foreach($locations as $name) if(!term_exists($name,'doctor_location'))wp_insert_term($name,'doctor_location');
    $rows=array(
      array('Dr Amelia Hart','amelia-hart','Cardiology','Central Clinic','MD, FRCP','14','145','English, French','Mon–Thu'),array('Dr Daniel Lee','daniel-lee','General Medicine','Riverside Clinic','MBBS, MRCGP','11','95','English, German','Mon–Fri'),array('Dr Sofia Martin','sofia-martin','Dermatology','Central Clinic','MD, MRCP','9','125','English, Spanish, French','Tue–Sat'),array('Dr Noah Williams','noah-williams','Paediatrics','North Medical Centre','MBChB, MRCPCH','12','110','English','Mon–Thu'),array('Dr Elena Petrova','elena-petrova','Neurology','Central Clinic','MD, PhD','16','160','English, Russian','Wed–Fri'),array('Oliver Jensen','oliver-jensen','Physiotherapy','Riverside Clinic','MSc Physiotherapy','10','80','English, Danish, Swedish','Mon–Sat'),array('Dr Maija Ozola','maija-ozola','General Medicine','North Medical Centre','MD','8','90','English, Latvian, Russian','Mon–Fri'),array('Dr Erik Lindberg','erik-lindberg','Cardiology','Riverside Clinic','MD, PhD','15','150','English, Swedish, Norwegian','Tue–Fri')
    );
    foreach($rows as $i=>$row){[$title,$slug,$spec,$loc,$cred,$exp,$price,$langs,$avail]=$row; $existing=get_page_by_path($slug,OBJECT,'doctor'); $args=array('post_type'=>'doctor','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'menu_order'=>$i,'post_excerpt'=>sprintf(__('Specialist in %s with a clear, patient-centred approach.','wp-bbtheme-child-medicine'),$spec),'post_content'=>'<!-- wp:paragraph --><p>'.esc_html__('The clinician profile brings together biography, areas of interest, qualifications and practical patient information.','wp-bbtheme-child-medicine').'</p><!-- /wp:paragraph -->'); if($existing){$args['ID']=$existing->ID;$id=wp_update_post($args);}else{$id=wp_insert_post($args);} if(!$id||is_wp_error($id))continue; wp_set_object_terms($id,$spec,'doctor_speciality');wp_set_object_terms($id,$loc,'doctor_location'); foreach(array('credentials'=>$cred,'experience'=>$exp,'consultation_price'=>$price,'languages'=>$langs,'availability'=>$avail)as $k=>$v)update_post_meta($id,'_doctor_'.$k,$v); $img=wpbb_medicine_demo_image($slug,$title); if($img)set_post_thumbnail($id,$img); update_post_meta($id,'_wp_theme_demo_doctor',1);}
}
add_action('wp_theme_seed_sector_pages','wpbb_medicine_seed_doctors',10);

function wpbb_medicine_seed_pages($profile){
    if(($profile['id']??'')!=='medicine')return;
    $pages=array('appointments'=>array(__('Book an appointment','wp-bbtheme-child-medicine'),'<!-- wp:group {"className":"medicine-booking-section"} --><div class="wp-block-group medicine-booking-section"><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><!-- wp:wpbb/booking-calendar {"title":"Book an appointment","intro":"Choose a doctor, service, date and time. We will confirm the request by email.","providerPostType":"doctor","providerLabel":"Doctor","services":"Consultation|30|85\\nFollow-up|20|55\\nVideo consultation|30|75","startTime":"09:00","endTime":"17:00","slotMinutes":30} /--><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --></div><!-- /wp:group -->'));
    foreach($pages as $slug=>$row){$existing=get_page_by_path($slug);$args=array('post_type'=>'page','post_status'=>'publish','post_title'=>$row[0],'post_name'=>$slug,'post_content'=>$row[1]);if($existing){$args['ID']=$existing->ID;wp_update_post($args);}else wp_insert_post($args);}
}
add_action('wp_theme_seed_sector_pages','wpbb_medicine_seed_pages',20);

function wpbb_medicine_navigation($items,$profile){ if(($profile['id']??'')!=='medicine')return $items; array_splice($items,1,0,array(array('key'=>'doctors','title'=>__('Doctors','wp-bbtheme-child-medicine'),'type'=>'post_type_archive','object'=>'doctor','locations'=>array('header','footer')))); $items[]=array('key'=>'appointments','title'=>__('Book appointment','wp-bbtheme-child-medicine'),'slug'=>'appointments','locations'=>array('header','footer','top')); return $items; }
add_filter('wp_theme_demo_navigation_items','wpbb_medicine_navigation',20,2);

function wpbb_medicine_doctor_query_args($request=array()){
    $args=array('post_type'=>'doctor','post_status'=>'publish','posts_per_page'=>12,'orderby'=>array('menu_order'=>'ASC','title'=>'ASC'));
    $search=sanitize_text_field($request['search']??''); if($search)$args['s']=$search;
    $tax=array(); foreach(array('speciality'=>'doctor_speciality','location'=>'doctor_location')as $key=>$taxonomy){$value=sanitize_title($request[$key]??'');if($value)$tax[]=array('taxonomy'=>$taxonomy,'field'=>'slug','terms'=>$value);} if($tax)$args['tax_query']=$tax;
    return $args;
}
function wpbb_medicine_doctor_card($id){
    $spec=wp_get_post_terms($id,'doctor_speciality',array('fields'=>'names'));$loc=wp_get_post_terms($id,'doctor_location',array('fields'=>'names'));$cred=get_post_meta($id,'_doctor_credentials',true);$exp=get_post_meta($id,'_doctor_experience',true);$price=get_post_meta($id,'_doctor_consultation_price',true);$image=get_the_post_thumbnail_url($id,'large');$media=function_exists('wp_theme_item_gallery_card_inner')?wp_theme_item_gallery_card_inner($id,'large',4):($image?'<img src="'.esc_url($image).'" alt="'.esc_attr(get_the_title($id)).'" loading="lazy">':'');
    return '<article class="medicine-doctor-card motion-fade-up"><a class="medicine-doctor-card__media" href="'.esc_url(get_permalink($id)).'">'.$media.'<span class="medicine-doctor-card__badge">'.esc_html($cred?:__('Clinician','wp-bbtheme-child-medicine')).'</span></a><div class="medicine-doctor-card__body"><div class="medicine-doctor-card__speciality">'.esc_html($spec[0]??'').'</div><h3><a href="'.esc_url(get_permalink($id)).'">'.esc_html(get_the_title($id)).'</a></h3><div class="medicine-doctor-card__meta"><span>'.esc_html($loc[0]??'').'</span>'.($exp?'<span>'.absint($exp).' '.esc_html__('years experience','wp-bbtheme-child-medicine').'</span>':'').($price?'<span>'.esc_html__('From','wp-bbtheme-child-medicine').' '.esc_html($price).'</span>':'').'</div><div class="medicine-doctor-card__actions"><a class="btn btn-outline-primary" href="'.esc_url(get_permalink($id)).'">'.esc_html__('Profile','wp-bbtheme-child-medicine').'</a><a class="btn btn-primary" href="'.esc_url(add_query_arg('doctor',$id,wp_theme_demo_page_url('appointments'))).'">'.esc_html__('Book','wp-bbtheme-child-medicine').'</a></div></div></article>';
}
function wpbb_medicine_directory_results($request=array()){$q=new WP_Query(wpbb_medicine_doctor_query_args($request));$html='<div class="row medicine-doctor-grid">';if($q->have_posts()){while($q->have_posts()){$q->the_post();$html.='<div class="col-12 col-md-6 col-xl-4">'.wpbb_medicine_doctor_card(get_the_ID()).'</div>';}}else{$html.='<div class="col-12"><div class="alert alert-light">'.esc_html__('No doctors match those filters. Try another speciality or location.','wp-bbtheme-child-medicine').'</div></div>';}wp_reset_postdata();return $html.'</div>';}
function wpbb_medicine_doctor_directory_shortcode(){
    $specs=get_terms(array('taxonomy'=>'doctor_speciality','hide_empty'=>false));$locs=get_terms(array('taxonomy'=>'doctor_location','hide_empty'=>false));ob_start();?><section class="medicine-doctor-directory" data-doctor-directory><div class="container"><div class="medicine-doctor-toolbar"><form data-doctor-filter><div class="row g-3"><div class="col-12 col-lg-5"><label><?php esc_html_e('Doctor or keyword','wp-bbtheme-child-medicine'); ?></label><input type="search" name="search" placeholder="<?php esc_attr_e('Search doctors…','wp-bbtheme-child-medicine'); ?>"></div><div class="col-12 col-md-5 col-lg-3"><label><?php esc_html_e('Speciality','wp-bbtheme-child-medicine'); ?></label><select name="speciality"><option value=""><?php esc_html_e('All specialities','wp-bbtheme-child-medicine'); ?></option><?php foreach($specs as $term):?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option><?php endforeach;?></select></div><div class="col-12 col-md-5 col-lg-3"><label><?php esc_html_e('Location','wp-bbtheme-child-medicine'); ?></label><select name="location"><option value=""><?php esc_html_e('All locations','wp-bbtheme-child-medicine'); ?></option><?php foreach($locs as $term):?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option><?php endforeach;?></select></div><div class="col-12 col-md-2 col-lg-1 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit" aria-label="<?php esc_attr_e('Filter doctors','wp-bbtheme-child-medicine'); ?>">→</button></div></div></form></div><div class="medicine-doctor-results" data-doctor-results aria-live="polite"><?php echo wpbb_medicine_directory_results(); ?></div></div></section><?php return ob_get_clean();
}
add_shortcode('wp_theme_doctor_directory','wpbb_medicine_doctor_directory_shortcode');
function wpbb_medicine_sector_finder_render_v37( $html, $context, $attributes ) {
    if ( 'medicine-doctors' !== $context ) return $html;
    return wpbb_medicine_doctor_directory_shortcode();
}
add_filter( 'wp_theme_sector_finder_render', 'wpbb_medicine_sector_finder_render_v37', 20, 3 );
function wpbb_medicine_ajax_doctors(){check_ajax_referer('wpbb_medicine_doctors','nonce');$request=array('search'=>sanitize_text_field(wp_unslash($_POST['search']??'')),'speciality'=>sanitize_key(wp_unslash($_POST['speciality']??'')),'location'=>sanitize_key(wp_unslash($_POST['location']??'')));wp_send_json_success(array('html'=>wpbb_medicine_directory_results($request)));}
add_action('wp_ajax_wpbb_medicine_doctors','wpbb_medicine_ajax_doctors');add_action('wp_ajax_nopriv_wpbb_medicine_doctors','wpbb_medicine_ajax_doctors');

function wpbb_medicine_after_hero($content,$profile){if(($profile['id']??'')!=='medicine')return $content;ob_start();include get_stylesheet_directory().'/patterns/medicine-search.php';include get_stylesheet_directory().'/patterns/medicine-specialities.php';return $content.ob_get_clean();}
add_filter('wp_theme_demo_after_hero_sections','wpbb_medicine_after_hero',20,2);
function wpbb_medicine_extra_home($content,$profile){if(($profile['id']??'')!=='medicine')return $content;ob_start();include get_stylesheet_directory().'/patterns/medicine-doctors.php';include get_stylesheet_directory().'/patterns/medicine-booking.php';include get_stylesheet_directory().'/patterns/medicine-trust.php';return $content.ob_get_clean();}
add_filter('wp_theme_demo_extra_home_sections','wpbb_medicine_extra_home',20,2);

function wpbb_medicine_single_content($content){if(!is_singular('doctor')||!in_the_loop()||!is_main_query())return $content;$id=get_the_ID();$spec=wp_get_post_terms($id,'doctor_speciality',array('fields'=>'names'));$loc=wp_get_post_terms($id,'doctor_location',array('fields'=>'names'));$image=get_the_post_thumbnail_url($id,'large');$gallery=function_exists('wp_theme_item_gallery_single_markup')?wp_theme_item_gallery_single_markup($id):'';$facts=array(__('Credentials','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_credentials',true),__('Experience','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_experience',true).' '.__('years','wp-bbtheme-child-medicine'),__('Languages','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_languages',true),__('Availability','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_availability',true));$html='<section class="medicine-doctor-single"><div class="container"><div class="medicine-doctor-profile"><div class="row g-0"><div class="col-12 col-lg-5 medicine-doctor-profile__media">'.($gallery?:($image?'<img src="'.esc_url($image).'" alt="'.esc_attr(get_the_title()).'">':'')).'</div><div class="col-12 col-lg-7 medicine-doctor-profile__content"><p class="wp-theme-sector-eyebrow">'.esc_html($spec[0]??'').'</p><h1>'.esc_html(get_the_title()).'</h1><p class="text-secondary">'.esc_html($loc[0]??'').'</p><div class="medicine-doctor-profile__facts">';foreach($facts as $label=>$value)if(trim($value)!=='')$html.='<div><small>'.esc_html($label).'</small><strong>'.esc_html($value).'</strong></div>';$html.='</div><div class="medicine-doctor-biography">'.$content.'</div><p><a class="btn btn-primary" href="'.esc_url(add_query_arg('doctor',$id,wp_theme_demo_page_url('appointments'))).'">'.esc_html__('Book an appointment','wp-bbtheme-child-medicine').'</a></p></div></div></div></div></section>';return $html;}
add_filter('the_content','wpbb_medicine_single_content',20);

/** Editable doctor directory mega menu using the parent Brandsafe-style system. */
function wpbb_medicine_mega_menu_definitions( $definitions, $profile ) {
    if ( empty( $profile['id'] ) || 'medicine' !== $profile['id'] ) return $definitions;
    $archive = get_post_type_archive_link( 'doctor' ) ?: home_url( '/doctors/' );
    $definitions['doctors'] = array(
        'title'      => __( 'Doctors navigation', 'wp-bbtheme-child-medicine' ),
        'target_key' => 'doctors',
        'eyebrow'    => __( 'Care team', 'wp-bbtheme-child-medicine' ),
        'heading'    => __( 'Find the right specialist.', 'wp-bbtheme-child-medicine' ),
        'intro'      => __( 'Browse by speciality, location or appointment need.', 'wp-bbtheme-child-medicine' ),
        'columns'    => array(
            array( 'title' => __( 'Specialities', 'wp-bbtheme-child-medicine' ), 'links' => array(
                array( __( 'Cardiology', 'wp-bbtheme-child-medicine' ), __( 'Heart and cardiovascular care.', 'wp-bbtheme-child-medicine' ), add_query_arg( 'speciality', 'cardiology', $archive ) ),
                array( __( 'Dermatology', 'wp-bbtheme-child-medicine' ), __( 'Skin, hair and nail concerns.', 'wp-bbtheme-child-medicine' ), add_query_arg( 'speciality', 'dermatology', $archive ) ),
                array( __( 'General medicine', 'wp-bbtheme-child-medicine' ), __( 'Diagnosis, review and ongoing care.', 'wp-bbtheme-child-medicine' ), $archive ),
            ) ),
            array( 'title' => __( 'Appointments', 'wp-bbtheme-child-medicine' ), 'links' => array(
                array( __( 'Book an appointment', 'wp-bbtheme-child-medicine' ), __( 'Choose a doctor, service, date and time.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'appointments' ) ),
                array( __( 'Video consultation', 'wp-bbtheme-child-medicine' ), __( 'Remote consultation where suitable.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'appointments' ) ),
                array( __( 'Locations', 'wp-bbtheme-child-medicine' ), __( 'Find the most convenient clinic.', 'wp-bbtheme-child-medicine' ), $archive ),
            ) ),
            array( 'title' => __( 'Patient information', 'wp-bbtheme-child-medicine' ), 'links' => array(
                array( __( 'About the practice', 'wp-bbtheme-child-medicine' ), __( 'How the care team works.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'about' ) ),
                array( __( 'Services', 'wp-bbtheme-child-medicine' ), __( 'Explore consultations and treatments.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'services' ) ),
                array( __( 'Contact', 'wp-bbtheme-child-medicine' ), __( 'Ask the patient support team.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'contact' ) ),
            ) ),
        ),
    );
    return $definitions;
}
add_filter( 'wp_theme_demo_mega_menu_definitions', 'wpbb_medicine_mega_menu_definitions', 20, 2 );

/** v3.5 sector editorial labels. */
function wpbb_medicine_blog_profile_v35( $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return $profile;
    $profile['blog_eyebrow'] = __( 'Health insights', 'wp-bbtheme-child-medicine' );
    $profile['blog_archive_title'] = __( 'Clear health guidance from our clinical team.', 'wp-bbtheme-child-medicine' );
    $profile['blog_archive_intro'] = __( 'Practical preparation, preventive care and specialist guidance written to make the next step easier to understand.', 'wp-bbtheme-child-medicine' );
    return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_medicine_blog_profile_v35', 90 );

/**
 * v3.6 Pharmacy quote catalogue.
 * This is intentionally non-WooCommerce: products are informational and create quote requests.
 */
function wpbb_medicine_register_pharmacy_content() {
    register_post_type( 'pharmacy_product', array(
        'labels' => array(
            'name'          => __( 'Pharmacy Products', 'wp-bbtheme-child-medicine' ),
            'singular_name' => __( 'Pharmacy Product', 'wp-bbtheme-child-medicine' ),
            'add_new_item'  => __( 'Add pharmacy product', 'wp-bbtheme-child-medicine' ),
        ),
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => 'pharmacy',
        'rewrite'      => array( 'slug' => 'pharmacy' ),
        'menu_icon'    => 'dashicons-plus-alt2',
        'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
    ) );
    register_taxonomy( 'pharmacy_category', 'pharmacy_product', array(
        'label'        => __( 'Pharmacy Categories', 'wp-bbtheme-child-medicine' ),
        'public'       => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite'      => array( 'slug' => 'pharmacy-category' ),
    ) );
    register_post_type( 'pharmacy_quote', array(
        'labels' => array(
            'name'          => __( 'Pharmacy Quotes', 'wp-bbtheme-child-medicine' ),
            'singular_name' => __( 'Pharmacy Quote', 'wp-bbtheme-child-medicine' ),
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=pharmacy_product',
        'exclude_from_search' => true,
        'menu_icon'           => 'dashicons-email-alt2',
        'supports'            => array( 'title' ),
    ) );
}
add_action( 'init', 'wpbb_medicine_register_pharmacy_content', 12 );

function wpbb_medicine_pharmacy_fields() {
    return array(
        'code'         => __( 'Product code', 'wp-bbtheme-child-medicine' ),
        'form'         => __( 'Form', 'wp-bbtheme-child-medicine' ),
        'strength'     => __( 'Strength / specification', 'wp-bbtheme-child-medicine' ),
        'pack'         => __( 'Pack size', 'wp-bbtheme-child-medicine' ),
        'availability' => __( 'Availability', 'wp-bbtheme-child-medicine' ),
        'quote_note'   => __( 'Quote note', 'wp-bbtheme-child-medicine' ),
    );
}
function wpbb_medicine_pharmacy_box() {
    add_meta_box( 'wpbb-pharmacy-product', __( 'Pharmacy product details', 'wp-bbtheme-child-medicine' ), 'wpbb_medicine_pharmacy_box_render', 'pharmacy_product', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'wpbb_medicine_pharmacy_box' );
function wpbb_medicine_pharmacy_box_render( $post ) {
    wp_nonce_field( 'wpbb_medicine_save_pharmacy', 'wpbb_medicine_pharmacy_nonce' );
    echo '<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px">';
    foreach ( wpbb_medicine_pharmacy_fields() as $key => $label ) {
        $value = get_post_meta( $post->ID, '_pharmacy_' . $key, true );
        echo '<label><strong>' . esc_html( $label ) . '</strong><input class="widefat" type="text" name="wpbb_pharmacy[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '"></label>';
    }
    echo '</div>';
}
function wpbb_medicine_save_pharmacy_product( $post_id ) {
    if ( empty( $_POST['wpbb_medicine_pharmacy_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpbb_medicine_pharmacy_nonce'] ) ), 'wpbb_medicine_save_pharmacy' ) || ! current_user_can( 'edit_post', $post_id ) ) return;
    $values = isset( $_POST['wpbb_pharmacy'] ) && is_array( $_POST['wpbb_pharmacy'] ) ? wp_unslash( $_POST['wpbb_pharmacy'] ) : array();
    foreach ( wpbb_medicine_pharmacy_fields() as $key => $label ) update_post_meta( $post_id, '_pharmacy_' . $key, sanitize_text_field( $values[ $key ] ?? '' ) );
}
add_action( 'save_post_pharmacy_product', 'wpbb_medicine_save_pharmacy_product' );

function wpbb_medicine_pharmacy_demo_image( $slug, $title ) {
    $existing = get_page_by_path( 'demo-pharmacy-' . $slug, OBJECT, 'attachment' );
    if ( $existing ) return $existing->ID;
    $source = get_stylesheet_directory() . '/assets/img/pharmacy/' . $slug . '.svg';
    if ( ! is_readable( $source ) ) return 0;
    $uploads = wp_upload_dir();
    $dir = trailingslashit( $uploads['basedir'] ) . 'wpbb-medicine-pharmacy';
    wp_mkdir_p( $dir );
    $target = $dir . '/' . $slug . '.svg';
    if ( ! file_exists( $target ) ) copy( $source, $target );
    $id = wp_insert_attachment( array( 'post_mime_type'=>'image/svg+xml', 'post_title'=>$title, 'post_name'=>'demo-pharmacy-' . $slug, 'post_status'=>'inherit' ), $target );
    if ( $id && ! is_wp_error( $id ) ) update_post_meta( $id, '_wp_attachment_image_alt', $title );
    return is_wp_error( $id ) ? 0 : $id;
}

function wpbb_medicine_seed_pharmacy_products( $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return;
    $categories = array( 'Everyday health', 'Skin care', 'Travel health', 'First aid' );
    foreach ( $categories as $name ) if ( ! term_exists( $name, 'pharmacy_category' ) ) wp_insert_term( $name, 'pharmacy_category' );
    $rows = array(
        array( 'Vitamin D3 Support', 'vitamin-d', 'Everyday health', 'PH-D3-1000', 'Capsules', '1000 IU', '60 capsules', 'Usually available', 'Suitable for a personalised pharmacy quote.' ),
        array( 'Dermatology Care Pack', 'skin-care', 'Skin care', 'PH-SKIN-01', 'Care set', 'Sensitive skin', '3 products', 'Pharmacist review', 'Request a quote for the recommended care combination.' ),
        array( 'Travel Health Kit', 'travel-kit', 'Travel health', 'PH-TRAVEL-02', 'Kit', 'Travel essentials', '8 items', 'Usually available', 'A pharmacist can adapt the kit to destination and trip length.' ),
        array( 'Allergy Support', 'allergy-relief', 'Everyday health', 'PH-ALL-10', 'Tablets', '10 mg', '30 tablets', 'Pharmacist review', 'Quote request includes a short suitability review.' ),
        array( 'Joint Support Pack', 'joint-support', 'Everyday health', 'PH-JOINT-01', 'Capsules', 'Daily support', '60 capsules', 'Usually available', 'Ask for a tailored pack and supply estimate.' ),
        array( 'Family First Aid Kit', 'first-aid', 'First aid', 'PH-FIRST-01', 'Kit', 'Home / travel', '24 items', 'Usually available', 'Request a quote for one or multiple kits.' ),
    );
    foreach ( $rows as $index => $row ) {
        list( $title, $slug, $category, $code, $form, $strength, $pack, $availability, $quote_note ) = $row;
        $existing = get_page_by_path( $slug, OBJECT, 'pharmacy_product' );
        $args = array(
            'post_type'    => 'pharmacy_product',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_name'    => $slug,
            'menu_order'   => $index,
            'post_excerpt' => sprintf( __( '%s with pharmacist-led quote support.', 'wp-bbtheme-child-medicine' ), $title ),
            'post_content' => '<!-- wp:paragraph --><p>' . esc_html__( 'Product information includes intended use, practical guidance, exclusions and the regulatory details patients need before requesting a quote.', 'wp-bbtheme-child-medicine' ) . '</p><!-- /wp:paragraph -->',
        );
        if ( $existing ) { $args['ID'] = $existing->ID; $id = wp_update_post( $args ); } else { $id = wp_insert_post( $args ); }
        if ( ! $id || is_wp_error( $id ) ) continue;
        wp_set_object_terms( $id, $category, 'pharmacy_category' );
        foreach ( compact( 'code','form','strength','pack','availability','quote_note' ) as $key => $value ) update_post_meta( $id, '_pharmacy_' . $key, $value );
        $image_id = wpbb_medicine_pharmacy_demo_image( $slug, $title );
        if ( $image_id ) set_post_thumbnail( $id, $image_id );
        update_post_meta( $id, '_wp_theme_demo_pharmacy_product', 1 );
    }
}
add_action( 'wp_theme_seed_sector_pages', 'wpbb_medicine_seed_pharmacy_products', 25 );

function wpbb_medicine_pharmacy_archive_url() {
    $url = get_post_type_archive_link( 'pharmacy_product' );
    return $url ?: home_url( '/pharmacy/' );
}

function wpbb_medicine_pharmacy_navigation( $items, $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return $items;
    $insert = array( array( 'key'=>'pharmacy', 'title'=>__( 'Pharmacy', 'wp-bbtheme-child-medicine' ), 'type'=>'post_type_archive', 'object'=>'pharmacy_product', 'locations'=>array('header','footer') ) );
    $position = 2;
    array_splice( $items, $position, 0, $insert );
    return $items;
}
add_filter( 'wp_theme_demo_navigation_items', 'wpbb_medicine_pharmacy_navigation', 25, 2 );

function wpbb_medicine_pharmacy_home_section( $content, $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return $content;
    $attrs = array( 'title'=>__( 'Pharmacy & health products', 'wp-bbtheme-child-medicine' ), 'postsToShow'=>6, 'postType'=>'pharmacy_product', 'taxonomy'=>'pharmacy_category', 'sortBy'=>'menu_order', 'sortOrder'=>'ASC', 'showImage'=>true, 'showExcerpt'=>true, 'className'=>'medicine-pharmacy-catalogue' );
    $section = '<!-- wp:group {"className":"wp-theme-section-shell medicine-pharmacy-section","layout":{"type":"default"}} --><div class="wp-block-group wp-theme-section-shell medicine-pharmacy-section"><!-- wp:wpbb/row {"containerClass":"container","customClasses":"wp-theme-section-heading align-items-end"} --><!-- wp:wpbb/column {"xs":12,"lg":8} -->' . wp_theme_demo_p( esc_html__( 'Clinic pharmacy','wp-bbtheme-child-medicine' ), 'wp-theme-sector-eyebrow' ) . wp_theme_demo_h( __( 'Health products with a quote-first pharmacy workflow.', 'wp-bbtheme-child-medicine' ), 2 ) . wp_theme_demo_p( esc_html__( 'Browse useful products, review the information and request a tailored quote instead of using a retail checkout.', 'wp-bbtheme-child-medicine' ) ) . '<!-- /wp:wpbb/column --><!-- wp:wpbb/column {"xs":12,"lg":4} -->' . wp_theme_demo_buttons( __( 'Browse pharmacy', 'wp-bbtheme-child-medicine' ), wpbb_medicine_pharmacy_archive_url() ) . '<!-- /wp:wpbb/column --><!-- /wp:wpbb/row --><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><!-- wp:wpbb/catalogue ' . wp_theme_demo_block_attrs( $attrs ) . ' /--><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --></div><!-- /wp:group -->';
    return $content . $section;
}
add_filter( 'wp_theme_demo_extra_home_sections', 'wpbb_medicine_pharmacy_home_section', 35, 2 );

function wpbb_medicine_pharmacy_quote_form( $product_id ) {
    $product_id = absint( $product_id );
    $success = isset( $_GET['quote'] ) && 'received' === sanitize_key( wp_unslash( $_GET['quote'] ) );
    ob_start();
    ?>
    <div class="medicine-pharmacy-quote-card" id="request-quote">
        <p class="wp-theme-sector-eyebrow"><?php esc_html_e( 'Request a quote', 'wp-bbtheme-child-medicine' ); ?></p>
        <h2><?php esc_html_e( 'Ask the pharmacy team for availability and pricing.', 'wp-bbtheme-child-medicine' ); ?></h2>
        <?php if ( $success ) : ?><div class="alert alert-success" role="status"><?php esc_html_e( 'Thanks. Your pharmacy quote request has been received.', 'wp-bbtheme-child-medicine' ); ?></div><?php endif; ?>
        <form class="wpbb-dynamic-form medicine-pharmacy-quote-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="wpbb_medicine_submit_quote">
            <input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
            <?php wp_nonce_field( 'wpbb_medicine_quote_' . $product_id, 'wpbb_medicine_quote_nonce' ); ?>
            <div class="row g-3">
                <div class="col-12 col-md-6"><label><?php esc_html_e( 'Name', 'wp-bbtheme-child-medicine' ); ?><input type="text" name="name" required autocomplete="name"></label></div>
                <div class="col-12 col-md-6"><label><?php esc_html_e( 'Email', 'wp-bbtheme-child-medicine' ); ?><input type="email" name="email" required autocomplete="email"></label></div>
                <div class="col-12 col-md-6"><label><?php esc_html_e( 'Phone', 'wp-bbtheme-child-medicine' ); ?><input type="tel" name="phone" autocomplete="tel"></label></div>
                <div class="col-12 col-md-6"><label><?php esc_html_e( 'Organisation', 'wp-bbtheme-child-medicine' ); ?><input type="text" name="company" autocomplete="organization"></label></div>
                <div class="col-12"><label><?php esc_html_e( 'What do you need?', 'wp-bbtheme-child-medicine' ); ?><textarea name="message" rows="5" placeholder="<?php esc_attr_e( 'Quantity, timing, delivery or any questions for the pharmacy team.', 'wp-bbtheme-child-medicine' ); ?>"></textarea></label></div>
                <div class="col-12"><label class="medicine-pharmacy-consent"><input type="checkbox" name="consent" value="1" required><span><?php esc_html_e( 'I agree that the clinic may use these details to respond to this quote request.', 'wp-bbtheme-child-medicine' ); ?></span></label></div>
                <div class="col-12"><button class="btn btn-primary" type="submit"><?php esc_html_e( 'Request pharmacy quote', 'wp-bbtheme-child-medicine' ); ?></button></div>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

function wpbb_medicine_submit_quote() {
    $product_id = absint( $_POST['product_id'] ?? 0 );
    if ( ! $product_id || 'pharmacy_product' !== get_post_type( $product_id ) ) wp_die( esc_html__( 'Invalid pharmacy product.', 'wp-bbtheme-child-medicine' ) );
    if ( empty( $_POST['wpbb_medicine_quote_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpbb_medicine_quote_nonce'] ) ), 'wpbb_medicine_quote_' . $product_id ) ) wp_die( esc_html__( 'The quote form expired. Please try again.', 'wp-bbtheme-child-medicine' ) );
    $name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $company = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
    $consent = ! empty( $_POST['consent'] );
    if ( '' === $name || ! is_email( $email ) || ! $consent ) wp_die( esc_html__( 'Please complete the required quote fields.', 'wp-bbtheme-child-medicine' ) );
    $quote_id = wp_insert_post( array( 'post_type'=>'pharmacy_quote', 'post_status'=>'publish', 'post_title'=>sprintf( '%s — %s', get_the_title( $product_id ), $name ) ) );
    if ( $quote_id && ! is_wp_error( $quote_id ) ) {
        foreach ( array( 'product_id'=>$product_id, 'name'=>$name, 'email'=>$email, 'phone'=>$phone, 'company'=>$company, 'message'=>$message, 'status'=>'new' ) as $key => $value ) update_post_meta( $quote_id, '_pharmacy_quote_' . $key, $value );
        $recipient = get_option( 'admin_email' );
        wp_mail( $recipient, sprintf( __( 'Pharmacy quote request: %s', 'wp-bbtheme-child-medicine' ), get_the_title( $product_id ) ), "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nOrganisation: {$company}\nProduct: " . get_the_title( $product_id ) . "\n\n{$message}" );
    }
    wp_safe_redirect( add_query_arg( 'quote', 'received', get_permalink( $product_id ) ) . '#request-quote' );
    exit;
}
add_action( 'admin_post_wpbb_medicine_submit_quote', 'wpbb_medicine_submit_quote' );
add_action( 'admin_post_nopriv_wpbb_medicine_submit_quote', 'wpbb_medicine_submit_quote' );

function wpbb_medicine_pharmacy_product_content( $content ) {
    if ( ! is_singular( 'pharmacy_product' ) || ! in_the_loop() || ! is_main_query() ) return $content;
    $id = get_the_ID();
    $image = get_the_post_thumbnail_url( $id, 'large' );
    $gallery = function_exists( 'wp_theme_item_gallery_single_markup' ) ? wp_theme_item_gallery_single_markup( $id ) : '';
    $terms = wp_get_post_terms( $id, 'pharmacy_category', array( 'fields'=>'names' ) );
    $facts = array(
        __( 'Product code', 'wp-bbtheme-child-medicine' ) => get_post_meta( $id, '_pharmacy_code', true ),
        __( 'Form', 'wp-bbtheme-child-medicine' ) => get_post_meta( $id, '_pharmacy_form', true ),
        __( 'Strength', 'wp-bbtheme-child-medicine' ) => get_post_meta( $id, '_pharmacy_strength', true ),
        __( 'Pack size', 'wp-bbtheme-child-medicine' ) => get_post_meta( $id, '_pharmacy_pack', true ),
        __( 'Availability', 'wp-bbtheme-child-medicine' ) => get_post_meta( $id, '_pharmacy_availability', true ),
    );
    $html = '<section class="medicine-pharmacy-single"><div class="container"><div class="row g-5 align-items-start"><div class="col-12 col-lg-6">' . ( $gallery ? $gallery : ( $image ? '<div class="medicine-pharmacy-single__media"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>' : '' ) ) . '</div><div class="col-12 col-lg-6"><p class="wp-theme-sector-eyebrow">' . esc_html( $terms[0] ?? __( 'Clinic pharmacy', 'wp-bbtheme-child-medicine' ) ) . '</p><h1>' . esc_html( get_the_title() ) . '</h1><p class="medicine-pharmacy-single__lead">' . esc_html( get_the_excerpt() ) . '</p><div class="medicine-pharmacy-facts">';
    foreach ( $facts as $label => $value ) if ( '' !== trim( (string) $value ) ) $html .= '<div><small>' . esc_html( $label ) . '</small><strong>' . esc_html( $value ) . '</strong></div>';
    $html .= '</div><a class="btn btn-primary" href="#request-quote">' . esc_html__( 'Request a quote', 'wp-bbtheme-child-medicine' ) . '</a></div></div><div class="medicine-pharmacy-content">' . $content . '</div>' . wpbb_medicine_pharmacy_quote_form( $id ) . '</div></section>';
    return $html;
}
add_filter( 'the_content', 'wpbb_medicine_pharmacy_product_content', 25 );

function wpbb_medicine_quote_columns( $columns ) {
    return array( 'cb'=>$columns['cb'], 'title'=>__( 'Quote', 'wp-bbtheme-child-medicine' ), 'product'=>__( 'Product', 'wp-bbtheme-child-medicine' ), 'customer'=>__( 'Customer', 'wp-bbtheme-child-medicine' ), 'status'=>__( 'Status', 'wp-bbtheme-child-medicine' ), 'date'=>$columns['date'] );
}
add_filter( 'manage_pharmacy_quote_posts_columns', 'wpbb_medicine_quote_columns' );
function wpbb_medicine_quote_column( $column, $post_id ) {
    if ( 'product' === $column ) { $product_id = absint( get_post_meta( $post_id, '_pharmacy_quote_product_id', true ) ); echo esc_html( get_the_title( $product_id ) ); }
    if ( 'customer' === $column ) echo esc_html( get_post_meta( $post_id, '_pharmacy_quote_name', true ) . ' · ' . get_post_meta( $post_id, '_pharmacy_quote_email', true ) );
    if ( 'status' === $column ) echo '<span class="status-new">' . esc_html( ucfirst( get_post_meta( $post_id, '_pharmacy_quote_status', true ) ?: 'new' ) ) . '</span>';
}
add_action( 'manage_pharmacy_quote_posts_custom_column', 'wpbb_medicine_quote_column', 10, 2 );

function wpbb_medicine_pharmacy_search_types( $types ) {
    if ( post_type_exists( 'pharmacy_product' ) ) $types[] = 'pharmacy_product';
    return array_values( array_unique( $types ) );
}
add_filter( 'wp_theme_header_search_post_types', 'wpbb_medicine_pharmacy_search_types' );

function wpbb_medicine_pharmacy_mega_menu( $definitions, $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'medicine' ) return $definitions;
    if ( isset( $definitions['doctors']['columns'][2]['links'] ) ) {
        $definitions['doctors']['columns'][2]['links'][] = array(
            __( 'Pharmacy', 'wp-bbtheme-child-medicine' ),
            __( 'Browse quote-first health products and ask the pharmacy team for availability.', 'wp-bbtheme-child-medicine' ),
            wpbb_medicine_pharmacy_archive_url(),
        );
    }
    $definitions['pharmacy'] = array(
        'title'      => __( 'Pharmacy navigation', 'wp-bbtheme-child-medicine' ),
        'target_key' => 'pharmacy',
        'eyebrow'    => __( 'Clinic pharmacy', 'wp-bbtheme-child-medicine' ),
        'heading'    => __( 'Products with pharmacist-led quote support.', 'wp-bbtheme-child-medicine' ),
        'intro'      => __( 'Browse by need, review useful information and request a tailored quote.', 'wp-bbtheme-child-medicine' ),
        'columns'    => array(
            array( 'title'=>__( 'Browse', 'wp-bbtheme-child-medicine' ), 'links'=>array(
                array( __( 'All pharmacy products', 'wp-bbtheme-child-medicine' ), __( 'See the complete quote catalogue.', 'wp-bbtheme-child-medicine' ), wpbb_medicine_pharmacy_archive_url() ),
                array( __( 'Everyday health', 'wp-bbtheme-child-medicine' ), __( 'Daily support and preventive products.', 'wp-bbtheme-child-medicine' ), add_query_arg( 'pharmacy_category', 'everyday-health', wpbb_medicine_pharmacy_archive_url() ) ),
                array( __( 'Travel health', 'wp-bbtheme-child-medicine' ), __( 'Travel kits and practical preparation.', 'wp-bbtheme-child-medicine' ), add_query_arg( 'pharmacy_category', 'travel-health', wpbb_medicine_pharmacy_archive_url() ) ),
            ) ),
            array( 'title'=>__( 'How quotes work', 'wp-bbtheme-child-medicine' ), 'links'=>array(
                array( __( 'Choose a product', 'wp-bbtheme-child-medicine' ), __( 'Review the specification and pack information.', 'wp-bbtheme-child-medicine' ), wpbb_medicine_pharmacy_archive_url() ),
                array( __( 'Request a quote', 'wp-bbtheme-child-medicine' ), __( 'Tell the team quantity, timing and delivery needs.', 'wp-bbtheme-child-medicine' ), wpbb_medicine_pharmacy_archive_url() ),
                array( __( 'Pharmacist response', 'wp-bbtheme-child-medicine' ), __( 'Receive availability, pricing and any suitability questions.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'contact' ) ),
            ) ),
            array( 'title'=>__( 'Patient support', 'wp-bbtheme-child-medicine' ), 'links'=>array(
                array( __( 'Book a clinician', 'wp-bbtheme-child-medicine' ), __( 'Book a medical appointment where clinical review is needed.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'appointments' ) ),
                array( __( 'Health insights', 'wp-bbtheme-child-medicine' ), __( 'Read preparation and preventive-care guidance.', 'wp-bbtheme-child-medicine' ), get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
                array( __( 'Contact', 'wp-bbtheme-child-medicine' ), __( 'Speak to patient or pharmacy support.', 'wp-bbtheme-child-medicine' ), wp_theme_demo_page_url( 'contact' ) ),
            ) ),
        ),
    );
    return $definitions;
}
add_filter( 'wp_theme_demo_mega_menu_definitions', 'wpbb_medicine_pharmacy_mega_menu', 35, 2 );

/** Make the pharmacy catalogue a first-class BBuilder variation in the editor. */
function wpbb_medicine_register_pharmacy_catalogue_variation_v36() {
    if ( ! wp_script_is( 'wp-blocks', 'enqueued' ) ) wp_enqueue_script( 'wp-blocks' );
    $script = <<<'JS'
(function(wp){
  if(!wp || !wp.blocks || !wp.blocks.registerBlockVariation) return;
  wp.blocks.registerBlockVariation('wpbb/catalogue',{
    name:'medicine-pharmacy-catalogue',
    title:'Pharmacy Catalogue',
    description:'Clinic pharmacy products with quote-first product cards.',
    icon:'store',
    attributes:{title:'Pharmacy & health products',postsToShow:6,postType:'pharmacy_product',taxonomy:'pharmacy_category',sortBy:'menu_order',sortOrder:'ASC',showImage:true,showExcerpt:true,className:'medicine-pharmacy-catalogue'},
    scope:['inserter','block'],
    isActive:function(attrs){return attrs && attrs.postType==='pharmacy_product';}
  });
})(window.wp);
JS;
    wp_add_inline_script( 'wp-blocks', $script, 'after' );
}
add_action( 'enqueue_block_editor_assets', 'wpbb_medicine_register_pharmacy_catalogue_variation_v36', 30 );

function wpbb_medicine_flush_rewrites_v36() {
    flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'wpbb_medicine_flush_rewrites_v36' );

/**
 * v3.8.10.20: keep editable Mega Menu content out of public discovery / SEO.
 * The parent already registers these objects as private; child filters make the
 * intent explicit for Core XML sitemaps and common SEO plugins too.
 */
function wpbb_child_private_megamenu_post_type_args( $args, $post_type ) {
    if ( 'megamenu' !== $post_type ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['exclude_from_search'] = true;
    $args['has_archive'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_post_type_args', 'wpbb_child_private_megamenu_post_type_args', 20, 2 );

function wpbb_child_private_megamenu_taxonomy_args( $args, $taxonomy ) {
    if ( 'megamenu-cat' !== $taxonomy ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_taxonomy_args', 'wpbb_child_private_megamenu_taxonomy_args', 20, 2 );

function wpbb_child_core_sitemap_post_types( $post_types ) {
    unset( $post_types['megamenu'] );
    return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'wpbb_child_core_sitemap_post_types', 20 );

function wpbb_child_core_sitemap_taxonomies( $taxonomies ) {
    unset( $taxonomies['megamenu-cat'] );
    return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'wpbb_child_core_sitemap_taxonomies', 20 );

function wpbb_child_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
}
add_filter( 'wp_robots', 'wpbb_child_mega_robots', 20 );

function wpbb_child_yoast_exclude_megamenu_post_type( $excluded, $post_type ) {
    return 'megamenu' === $post_type ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'wpbb_child_yoast_exclude_megamenu_post_type', 20, 2 );

function wpbb_child_yoast_exclude_megamenu_taxonomy( $excluded, $taxonomy ) {
    return 'megamenu-cat' === $taxonomy ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'wpbb_child_yoast_exclude_megamenu_taxonomy', 20, 2 );

function wpbb_child_yoast_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) return 'noindex, nofollow';
    return $robots;
}
add_filter( 'wpseo_robots', 'wpbb_child_yoast_mega_robots', 20 );


/**
 * v3.8.10.21: global request-a-quote UI is opt-in by child theme.
 * Sector themes with their own quote journeys can keep it; the rest do not
 * expose an unrelated floating "My Quote" control or public route.
 */
if ( ! function_exists( 'wpbb_child_request_quote_enabled' ) ) {
    function wpbb_child_request_quote_enabled() {
        $enabled_themes = array(
            'wp-bbtheme-child-automotive',
            'wp-bbtheme-child-building-services',
            'wp-bbtheme-child-insurance',
            'wp-bbtheme-child-logistics',
            'wp-bbtheme-child-medicine',
            'wp-bbtheme-child-woo-tech-shop',
        );
        $enabled = in_array( get_stylesheet(), $enabled_themes, true );
        return (bool) apply_filters( 'wpbb_child_request_quote_enabled', $enabled, get_stylesheet() );
    }
}

function wpbb_child_request_quote_body_class( $classes ) {
    $classes[] = wpbb_child_request_quote_enabled() ? 'wpbb-request-quote-enabled' : 'wpbb-request-quote-disabled';
    return $classes;
}
add_filter( 'body_class', 'wpbb_child_request_quote_body_class', 30 );

function wpbb_child_request_quote_menu_items( $items ) {
    if ( wpbb_child_request_quote_enabled() ) return $items;
    $target = trim( (string) wp_parse_url( home_url( '/request-a-quote/' ), PHP_URL_PATH ), '/' );
    foreach ( $items as $key => $item ) {
        $path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
        if ( $target && $path === $target ) unset( $items[ $key ] );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'wpbb_child_request_quote_menu_items', 30 );

function wpbb_child_request_quote_disable_route() {
    if ( wpbb_child_request_quote_enabled() ) return;
    $request = isset( $GLOBALS['wp']->request ) ? trim( (string) $GLOBALS['wp']->request, '/' ) : '';
    if ( ! is_page( 'request-a-quote' ) && 'request-a-quote' !== $request ) return;

    global $wp_query;
    if ( $wp_query ) $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
    $template = get_404_template();
    if ( $template ) {
        include $template;
        exit;
    }
    wp_die( esc_html__( 'Page not found.', 'wp-bbtheme-child' ), esc_html__( 'Not found', 'wp-bbtheme-child' ), array( 'response' => 404 ) );
}
add_action( 'template_redirect', 'wpbb_child_request_quote_disable_route', 1 );

function wpbb_child_request_quote_sitemap_args( $args, $post_type ) {
    if ( wpbb_child_request_quote_enabled() || 'page' !== $post_type ) return $args;
    $page = get_page_by_path( 'request-a-quote' );
    if ( $page ) {
        $excluded = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
        $excluded[] = (int) $page->ID;
        $args['post__not_in'] = array_values( array_unique( $excluded ) );
    }
    return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'wpbb_child_request_quote_sitemap_args', 30, 2 );

require_once get_stylesheet_directory() . '/inc/seo-guardrails.php';

/** v3.8.10.24: identify generated legal pages independently of translated slugs. */
function wpbb_child_legal_page_body_class_v381024( $classes ) {
    if ( ! is_page() ) return $classes;
    $post = get_queried_object();
    if ( ! $post instanceof WP_Post ) return $classes;

    $is_legal = function_exists( 'is_privacy_policy' ) && is_privacy_policy();
    if ( ! $is_legal && false !== strpos( (string) $post->post_content, 'wp-theme-legal-section' ) ) {
        $is_legal = true;
    }
    if ( $is_legal ) $classes[] = 'wpbb-legal-page';
    return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'wpbb_child_legal_page_body_class_v381024', 40 );

/** v3.8.10.25: remove generated empty spacing without touching authored copy. */
if ( ! function_exists( 'wpbb_child_remove_empty_paragraphs_v381025' ) ) {
    function wpbb_child_remove_empty_paragraphs_v381025( $content ) {
        if ( is_admin() || ! is_string( $content ) || '' === $content ) return $content;
        return (string) preg_replace(
            '~<p(?:\\s[^>]*)?>(?:\\s|&nbsp;|&#160;|<br\\s*/?>)*</p>~i',
            '',
            $content
        );
    }
}
add_filter( 'the_content', 'wpbb_child_remove_empty_paragraphs_v381025', 120 );

/** v3.8.10.25: do not output a completely empty CTA block above the footer. */
if ( ! function_exists( 'wpbb_child_remove_empty_cta_v381025' ) ) {
    function wpbb_child_remove_empty_cta_v381025( $block_content, $block ) {
        if ( empty( $block['blockName'] ) || 'wpbb/cta-section' !== $block['blockName'] || ! is_string( $block_content ) ) return $block_content;
        if ( preg_match( '~<(?:img|picture|video|iframe|form|button|a)\\b~i', $block_content ) ) return $block_content;
        $plain = trim( html_entity_decode( wp_strip_all_tags( $block_content ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) ) );
        return '' === $plain ? '' : $block_content;
    }
}
add_filter( 'render_block', 'wpbb_child_remove_empty_cta_v381025', 120, 2 );

