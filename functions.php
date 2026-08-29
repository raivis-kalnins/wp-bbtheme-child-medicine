<?php
defined( 'ABSPATH' ) || exit;

function wpbb_medicine_project_mode( $mode ) { return 'medicine'; }
add_filter( 'wp_theme_project_mode', 'wpbb_medicine_project_mode' );

function wpbb_medicine_assets() {
    $theme = wp_get_theme();
    wp_enqueue_style( 'wpbb-medicine-meta', get_stylesheet_uri(), array( 'wp-theme-style' ), $theme->get( 'Version' ) );
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if ( ! is_readable( $manifest ) ) return;
    $data = json_decode( (string) file_get_contents( $manifest ), true );
    if ( ! is_array( $data ) ) return;
    if ( ! empty( $data['src/scss/public.scss']['file'] ) ) {
        wp_enqueue_style( 'wpbb-medicine-app', get_stylesheet_directory_uri() . '/dist/' . ltrim( $data['src/scss/public.scss']['file'], '/' ), array( 'wpbb-medicine-meta' ), $theme->get( 'Version' ) );
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
        return has_shortcode( $content, 'wp_theme_doctor_directory' );
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
    $profile['stats']=array(array('24+',__('Clinical specialists','wp-bbtheme-child-medicine')),array('8',__('Core specialities','wp-bbtheme-child-medicine')),array('4.9',__('Demo patient rating','wp-bbtheme-child-medicine')),array('<24h',__('Typical response','wp-bbtheme-child-medicine')));
    $profile['process']=array(array('01',__('Find','wp-bbtheme-child-medicine'),__('Filter by speciality, location and the clinician who fits your needs.','wp-bbtheme-child-medicine')),array('02',__('Book','wp-bbtheme-child-medicine'),__('Choose a service, date and available time in the appointment block.','wp-bbtheme-child-medicine')),array('03',__('Attend','wp-bbtheme-child-medicine'),__('Receive confirmation and arrive with the right clinical team already selected.','wp-bbtheme-child-medicine')));
    $profile['about_title']=__('Human clinical expertise with a clearer digital front door.','wp-bbtheme-child-medicine');
    $profile['about_text']=__('The demo combines doctor profiles, specialities, AJAX discovery and a reusable BBuilder appointment block in a calm Bootstrap-based system.','wp-bbtheme-child-medicine');
    $profile['cta_title']=__('Need help choosing the right specialist?','wp-bbtheme-child-medicine'); $profile['cta_text']=__('Contact the care team or start with the doctor directory and book a suitable appointment.','wp-bbtheme-child-medicine');
    $profile['footer_text']=__('A modern medical directory and appointment starter built with WordPress, Bootstrap and Gutenberg.','wp-bbtheme-child-medicine');
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
    foreach($rows as $i=>$row){[$title,$slug,$spec,$loc,$cred,$exp,$price,$langs,$avail]=$row; $existing=get_page_by_path($slug,OBJECT,'doctor'); $args=array('post_type'=>'doctor','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'menu_order'=>$i,'post_excerpt'=>sprintf(__('Specialist in %s with a clear, patient-centred approach.','wp-bbtheme-child-medicine'),$spec),'post_content'=>'<!-- wp:paragraph --><p>'.esc_html__('This is demonstration profile content. Replace it with the clinician biography, areas of interest, qualifications and patient information.','wp-bbtheme-child-medicine').'</p><!-- /wp:paragraph -->'); if($existing){$args['ID']=$existing->ID;$id=wp_update_post($args);}else{$id=wp_insert_post($args);} if(!$id||is_wp_error($id))continue; wp_set_object_terms($id,$spec,'doctor_speciality');wp_set_object_terms($id,$loc,'doctor_location'); foreach(array('credentials'=>$cred,'experience'=>$exp,'consultation_price'=>$price,'languages'=>$langs,'availability'=>$avail)as $k=>$v)update_post_meta($id,'_doctor_'.$k,$v); $img=wpbb_medicine_demo_image($slug,$title); if($img)set_post_thumbnail($id,$img); update_post_meta($id,'_wp_theme_demo_doctor',1);}
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
    $spec=wp_get_post_terms($id,'doctor_speciality',array('fields'=>'names'));$loc=wp_get_post_terms($id,'doctor_location',array('fields'=>'names'));$cred=get_post_meta($id,'_doctor_credentials',true);$exp=get_post_meta($id,'_doctor_experience',true);$price=get_post_meta($id,'_doctor_consultation_price',true);$image=get_the_post_thumbnail_url($id,'large');
    return '<article class="medicine-doctor-card motion-fade-up"><a class="medicine-doctor-card__media" href="'.esc_url(get_permalink($id)).'">'.($image?'<img src="'.esc_url($image).'" alt="'.esc_attr(get_the_title($id)).'" loading="lazy">':'').'<span class="medicine-doctor-card__badge">'.esc_html($cred?:__('Clinician','wp-bbtheme-child-medicine')).'</span></a><div class="medicine-doctor-card__body"><div class="medicine-doctor-card__speciality">'.esc_html($spec[0]??'').'</div><h3><a href="'.esc_url(get_permalink($id)).'">'.esc_html(get_the_title($id)).'</a></h3><div class="medicine-doctor-card__meta"><span>'.esc_html($loc[0]??'').'</span>'.($exp?'<span>'.absint($exp).' '.esc_html__('years experience','wp-bbtheme-child-medicine').'</span>':'').($price?'<span>'.esc_html__('From','wp-bbtheme-child-medicine').' '.esc_html($price).'</span>':'').'</div><div class="medicine-doctor-card__actions"><a class="btn btn-outline-primary" href="'.esc_url(get_permalink($id)).'">'.esc_html__('Profile','wp-bbtheme-child-medicine').'</a><a class="btn btn-primary" href="'.esc_url(add_query_arg('doctor',$id,wp_theme_demo_page_url('appointments'))).'">'.esc_html__('Book','wp-bbtheme-child-medicine').'</a></div></div></article>';
}
function wpbb_medicine_directory_results($request=array()){$q=new WP_Query(wpbb_medicine_doctor_query_args($request));$html='<div class="row medicine-doctor-grid">';if($q->have_posts()){while($q->have_posts()){$q->the_post();$html.='<div class="col-12 col-md-6 col-xl-4">'.wpbb_medicine_doctor_card(get_the_ID()).'</div>';}}else{$html.='<div class="col-12"><div class="alert alert-light">'.esc_html__('No doctors match those filters. Try another speciality or location.','wp-bbtheme-child-medicine').'</div></div>';}wp_reset_postdata();return $html.'</div>';}
function wpbb_medicine_doctor_directory_shortcode(){
    $specs=get_terms(array('taxonomy'=>'doctor_speciality','hide_empty'=>false));$locs=get_terms(array('taxonomy'=>'doctor_location','hide_empty'=>false));ob_start();?><section class="medicine-doctor-directory" data-doctor-directory><div class="container"><div class="medicine-doctor-toolbar"><form data-doctor-filter><div class="row g-3"><div class="col-12 col-lg-5"><label><?php esc_html_e('Doctor or keyword','wp-bbtheme-child-medicine'); ?></label><input type="search" name="search" placeholder="<?php esc_attr_e('Search doctors…','wp-bbtheme-child-medicine'); ?>"></div><div class="col-12 col-md-5 col-lg-3"><label><?php esc_html_e('Speciality','wp-bbtheme-child-medicine'); ?></label><select name="speciality"><option value=""><?php esc_html_e('All specialities','wp-bbtheme-child-medicine'); ?></option><?php foreach($specs as $term):?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option><?php endforeach;?></select></div><div class="col-12 col-md-5 col-lg-3"><label><?php esc_html_e('Location','wp-bbtheme-child-medicine'); ?></label><select name="location"><option value=""><?php esc_html_e('All locations','wp-bbtheme-child-medicine'); ?></option><?php foreach($locs as $term):?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option><?php endforeach;?></select></div><div class="col-12 col-md-2 col-lg-1 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit" aria-label="<?php esc_attr_e('Filter doctors','wp-bbtheme-child-medicine'); ?>">→</button></div></div></form></div><div class="medicine-doctor-results" data-doctor-results aria-live="polite"><?php echo wpbb_medicine_directory_results(); ?></div></div></section><?php return ob_get_clean();
}
add_shortcode('wp_theme_doctor_directory','wpbb_medicine_doctor_directory_shortcode');
function wpbb_medicine_ajax_doctors(){check_ajax_referer('wpbb_medicine_doctors','nonce');$request=array('search'=>sanitize_text_field(wp_unslash($_POST['search']??'')),'speciality'=>sanitize_key(wp_unslash($_POST['speciality']??'')),'location'=>sanitize_key(wp_unslash($_POST['location']??'')));wp_send_json_success(array('html'=>wpbb_medicine_directory_results($request)));}
add_action('wp_ajax_wpbb_medicine_doctors','wpbb_medicine_ajax_doctors');add_action('wp_ajax_nopriv_wpbb_medicine_doctors','wpbb_medicine_ajax_doctors');

function wpbb_medicine_after_hero($content,$profile){if(($profile['id']??'')!=='medicine')return $content;ob_start();include get_stylesheet_directory().'/patterns/medicine-search.php';include get_stylesheet_directory().'/patterns/medicine-specialities.php';return $content.ob_get_clean();}
add_filter('wp_theme_demo_after_hero_sections','wpbb_medicine_after_hero',20,2);
function wpbb_medicine_extra_home($content,$profile){if(($profile['id']??'')!=='medicine')return $content;ob_start();include get_stylesheet_directory().'/patterns/medicine-doctors.php';include get_stylesheet_directory().'/patterns/medicine-booking.php';include get_stylesheet_directory().'/patterns/medicine-trust.php';return $content.ob_get_clean();}
add_filter('wp_theme_demo_extra_home_sections','wpbb_medicine_extra_home',20,2);

function wpbb_medicine_single_content($content){if(!is_singular('doctor')||!in_the_loop()||!is_main_query())return $content;$id=get_the_ID();$spec=wp_get_post_terms($id,'doctor_speciality',array('fields'=>'names'));$loc=wp_get_post_terms($id,'doctor_location',array('fields'=>'names'));$image=get_the_post_thumbnail_url($id,'large');$facts=array(__('Credentials','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_credentials',true),__('Experience','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_experience',true).' '.__('years','wp-bbtheme-child-medicine'),__('Languages','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_languages',true),__('Availability','wp-bbtheme-child-medicine')=>get_post_meta($id,'_doctor_availability',true));$html='<section class="medicine-doctor-single"><div class="container"><div class="medicine-doctor-profile"><div class="row g-0"><div class="col-12 col-lg-5 medicine-doctor-profile__media">'.($image?'<img src="'.esc_url($image).'" alt="'.esc_attr(get_the_title()).'">':'').'</div><div class="col-12 col-lg-7 medicine-doctor-profile__content"><p class="wp-theme-sector-eyebrow">'.esc_html($spec[0]??'').'</p><h1>'.esc_html(get_the_title()).'</h1><p class="text-secondary">'.esc_html($loc[0]??'').'</p><div class="medicine-doctor-profile__facts">';foreach($facts as $label=>$value)if(trim($value)!=='')$html.='<div><small>'.esc_html($label).'</small><strong>'.esc_html($value).'</strong></div>';$html.='</div><div class="medicine-doctor-biography">'.$content.'</div><p><a class="btn btn-primary" href="'.esc_url(add_query_arg('doctor',$id,wp_theme_demo_page_url('appointments'))).'">'.esc_html__('Book an appointment','wp-bbtheme-child-medicine').'</a></p></div></div></div></div></section>';return $html;}
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
