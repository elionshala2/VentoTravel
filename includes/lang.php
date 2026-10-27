<?php
// Language handling: English default, Albanian optional, saved in a cookie (1 year)
const SUPPORTED_LANGS = ['en', 'sq'];

if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGS, true)) {
    setcookie('lang', $_GET['lang'], ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
    $_COOKIE['lang'] = $_GET['lang'];
    $params = $_GET; unset($params['lang']);
    $back = strtok($_SERVER['REQUEST_URI'], '?') . ($params ? '?' . http_build_query($params) : '');
    header('Location: ' . $back);
    exit;
}

if (isset($_GET['theme']) && in_array($_GET['theme'], ['dark','light'], true)) { setcookie('theme', $_GET['theme'], ['expires'=>time()+31536000,'path'=>'/','samesite'=>'Lax']); $_COOKIE['theme']=$_GET['theme']; }
$THEME = (($_COOKIE['theme'] ?? 'dark') === 'light') ? 'light' : 'dark';
$LANG = (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], SUPPORTED_LANGS, true)) ? $_COOKIE['lang'] : 'en';

$STR = [
'en' => [
 'brand_tag'=>'Travel beyond the ordinary',
 'nav_about'=>'About','nav_destinations'=>'Destinations','nav_book'=>'Book','nav_feedback'=>'Feedback','nav_faq'=>'FAQ','nav_history'=>'History','nav_logout'=>'Log out','nav_login'=>'Log in','nav_register'=>'Sign up','menu'=>'Menu','skip'=>'Skip to content',
 'nav_admin'=>'Dashboard','nav_adm_dest'=>'Destinations','nav_adm_book'=>'Bookings','nav_adm_fb'=>'Feedback','nav_adm_faq'=>'FAQ',
 'welcome'=>'Welcome','hello'=>'Hello',
 'offer'=>'🎉 New Year offer! 5% off all destinations booked before December 31st!','offer_cta'=>'Book now',
 'seo_home_t'=>'VentoTravel – Premium Travel Agency','seo_home_d'=>'VentoTravel designs unforgettable trips: curated destinations, personalised packages and stress-free travel across Europe and beyond.',
 'hero_eyebrow'=>'Your next adventure starts here','hero_title'=>'Discover the world, <em>effortlessly.</em>','hero_sub'=>'Curated destinations, personalised packages and a team that sweats the details – so you can simply enjoy the journey.','hero_cta1'=>'Explore destinations','hero_cta2'=>'Book a trip',
 'stat1'=>'Destinations','stat2'=>'Happy travellers','stat3'=>'Years of experience','stat4'=>'Support',
 'about_title'=>'About us','about_sub'=>'A travel agency with a rich history and a bold vision.',
 'about_p1'=>'VentoTravel is a travel agency with a rich history and a powerful vision of delivering the most amazing travel experiences. With an unwavering commitment to customer service and a passionate team of travel specialists, we have become a trusted name in travel and tourism.',
 'about_p2'=>'We offer a wide range of services: personalised packages, holidays in exotic destinations, organised tours and more. Thanks to a broad network of partners, our clients enjoy their journeys without stress or hassle.',
 'about_p3'=>'Quality and safety are at the core of our identity. A specialised team checks every trip we offer, and our attention to detail is what turns a journey into an unforgettable adventure.',
 'about_p4'=>'We respect the cultures and environments we visit, and we encourage every traveller to explore, learn from new experiences and share beautiful moments with their companions.',
 'f1_t'=>'Tailored packages','f1_d'=>'Trips designed around your pace, budget and interests.',
 'f2_t'=>'Safety first','f2_d'=>'Every itinerary is checked by our quality and safety team.',
 'f3_t'=>'Local partners','f3_d'=>'A trusted network that makes every journey smooth.',
 'signup_now'=>'Sign up now','cta_guest_sub'=>'Create a free account to book trips and unlock exclusive prices.',
 'cta_title'=>'Ready for your next trip?','cta_sub'=>'Join us and discover the world with VentoTravel.',
 'seo_dest_t'=>'Destinations – VentoTravel','seo_dest_d'=>'Browse our curated travel destinations with exclusive discounted prices.',
 'dest_title'=>'Destinations','dest_sub'=>'Hand-picked places, exclusive prices.','sort_by'=>'Sort by','sort_low'=>'Price: low to high','sort_high'=>'Price: high to low','sort_new'=>'Newest first','sort_old'=>'Oldest first',
 'price'=>'Price','only'=>'Only','book_this'=>'Book this trip','no_dest'=>'No destinations available right now, please try again later!','more'=>'Show more','less'=>'Show less','off5'=>'-5%',
 'seo_book_t'=>'Book a Trip – VentoTravel','seo_book_d'=>'Reserve your next trip with VentoTravel in just a few clicks.',
 'book_title'=>'Book your trip','book_sub'=>'Fill in your details and we will take care of the rest.','full_name'=>'Full name','email'=>'Email','phone'=>'Phone number','destination'=>'Destination','choose_dest'=>'Choose a destination','gender'=>'Gender','male'=>'Male','female'=>'Female','other'=>'Other','address'=>'Address','book_btn'=>'Confirm booking',
 'ok_booked'=>'Your trip was booked successfully!','err_generic'=>'Something went wrong, please try again.','err_dest'=>'The selected destination is not valid!',
 'seo_fb_t'=>'Feedback – VentoTravel','seo_fb_d'=>'Tell us what you think about VentoTravel.','fb_title'=>'Your feedback','fb_sub'=>'We read every message – it helps us get better.','message'=>'Message','send'=>'Send','ok_fb'=>'Feedback submitted successfully!',
 'seo_faq_t'=>'FAQ – VentoTravel','seo_faq_d'=>'Answers to the most frequently asked questions about VentoTravel.','faq_title'=>'Frequently Asked Questions','faq_sub'=>'Quick answers to common questions.','no_faq'=>'No questions yet.',
 'seo_hist_t'=>'My History – VentoTravel','seo_hist_d'=>'Your bookings and feedback.','hist_title'=>'Your history','hist_sub'=>'Manage your bookings and feedback.','bookings'=>'Bookings','feedbacks'=>'Feedback','no_bookings'=>'No bookings yet.','no_fb'=>'No feedback yet.','booking_id'=>'Booking ID','feedback_id'=>'Feedback ID','date'=>'Date','delete'=>'Delete','edit'=>'Edit','cancel'=>'Cancel','save'=>'Save changes','ok_deleted'=>'Deleted successfully!','confirm_del'=>'Are you sure you want to delete this?','dest_id'=>'Destination ID','user_id'=>'User ID',
 'seo_login_t'=>'Log in – VentoTravel','seo_login_d'=>'Log in to your VentoTravel account to book trips and manage your reservations.','login_title'=>'Welcome back','login_sub'=>'Log in to continue your journey.','password'=>'Password','login_btn'=>'Log in','no_account'=>"Don't have an account?",'signup_link'=>'Sign up','err_login'=>'Email or password is incorrect, please try again!',
 'seo_reg_t'=>'Sign up – VentoTravel','seo_reg_d'=>'Create your free VentoTravel account.','reg_title'=>'Create account','reg_sub'=>'It takes less than a minute.','username'=>'Username','confirm_pw'=>'Confirm password','reg_btn'=>'Sign up','has_account'=>'Already have an account?','login_link'=>'Log in now',
 'err_email_taken'=>'This email already exists, try another one!','err_email_admin'=>'Please try a different email!','err_user_admin'=>'Please try a different username!','err_pw'=>'Passwords do not match!','ok_reg'=>'Congratulations, you have registered successfully!',
 'adm_title'=>'Admin dashboard','adm_sub'=>'Manage destinations, bookings, feedback and FAQ.','adm_manage'=>'Manage','title'=>'Title','photo_url'=>'Photo URL','small_desc'=>'Short description','big_desc'=>'Long description','add'=>'Add','add_dest'=>'Add destination','edit_dest'=>'Edit destination','list_dest'=>'All destinations','list_book'=>'All bookings','list_fb'=>'All feedback','list_faq'=>'All questions','question'=>'Question','answer'=>'Answer','add_faq'=>'Add question','edit_faq'=>'Edit question','edit_book'=>'Edit booking','edit_fb'=>'Edit feedback','ok_added'=>'Added successfully!','ok_updated'=>'Updated successfully!','reservation_date'=>'Booking date','empty'=>'Nothing here yet.',
 'footer_rights'=>'All rights reserved.','footer_tag'=>'Travel beyond the ordinary.','lang_en'=>'English','lang_sq'=>'Shqip','switch_lang'=>'Change language','close'=>'Close',
],
'sq' => [
 'brand_tag'=>'Udhëto përtej të zakonshmes',
 'nav_about'=>'Rreth nesh','nav_destinations'=>'Destinacionet','nav_book'=>'Rezervo','nav_feedback'=>'Feedback','nav_faq'=>'FAQ','nav_history'=>'Historiku','nav_logout'=>'Dil','nav_login'=>'Kyçu','nav_register'=>'Regjistrohu','menu'=>'Menuja','skip'=>'Kalo te përmbajtja',
 'nav_admin'=>'Paneli','nav_adm_dest'=>'Destinacionet','nav_adm_book'=>'Rezervimet','nav_adm_fb'=>'Feedback','nav_adm_faq'=>'FAQ',
 'welcome'=>'Mirësevjen','hello'=>'Përshëndetje',
 'offer'=>'🎉 Oferta e vitit të ri! 5% zbritje në të gjitha destinacionet që rezervohen para 31 dhjetorit!','offer_cta'=>'Rezervo tani',
 'seo_home_t'=>'VentoTravel – Agjensi Udhëtimesh Premium','seo_home_d'=>'VentoTravel ofron udhëtime të paharrueshme: destinacione të përzgjedhura, paketa të personalizuara dhe udhëtime pa stres në Evropë e më gjerë.',
 'hero_eyebrow'=>'Aventura jote e radhës fillon këtu','hero_title'=>'Zbulo botën, <em>pa mund.</em>','hero_sub'=>'Destinacione të përzgjedhura, paketa të personalizuara dhe një ekip që kujdeset për çdo detaj – ti thjesht shijo udhëtimin.','hero_cta1'=>'Eksploro destinacionet','hero_cta2'=>'Rezervo udhëtimin',
 'stat1'=>'Destinacione','stat2'=>'Udhëtarë të kënaqur','stat3'=>'Vite përvojë','stat4'=>'Mbështetje',
 'about_title'=>'Rreth nesh','about_sub'=>'Një agjensi udhëtimesh me histori të pasur dhe vizion të guximshëm.',
 'about_p1'=>'VentoTravel është një agjensi udhëtimesh me një histori të pasur dhe një vizion të fuqishëm për të ofruar eksperiencat më të mahnitshme të udhëtimit. Me angazhim të palëkundur ndaj shërbimit të klientit dhe një ekip të pasionuar specialistësh, jemi bërë një referencë e njohur në botën e udhëtimeve dhe turizmit.',
 'about_p2'=>'Ofrojmë një spektër të gjerë shërbimesh: paketa të personalizuara, pushime në destinacione ekzotike, udhëtime të organizuara dhe më shumë. Me një rrjet të gjerë partnerësh, klientët tanë i shijojnë udhëtimet pa stres dhe mundime.',
 'about_p3'=>'Cilësia dhe siguria janë në qendër të identitetit tonë. Një ekip i specializuar kontrollon çdo udhëtim që ofrojmë, dhe kujdesi ndaj detajeve i kthen udhëtimet në aventura të paharrueshme.',
 'about_p4'=>'Respektojmë kulturat dhe mjediset që vizitojmë dhe inkurajojmë çdo udhëtar të eksplorojë, të mësojë nga përvojat e reja dhe të ndajë momente të bukura me bashkëudhëtarët.',
 'f1_t'=>'Paketa të personalizuara','f1_d'=>'Udhëtime të dizajnuara sipas ritmit, buxhetit dhe interesave tuaja.',
 'f2_t'=>'Siguria para së gjithash','f2_d'=>'Çdo itinerar kontrollohet nga ekipi ynë i cilësisë dhe sigurisë.',
 'f3_t'=>'Partnerë lokalë','f3_d'=>'Një rrjet i besuar që e bën çdo udhëtim të qetë.',
 'signup_now'=>'Regjistrohu tani','cta_guest_sub'=>'Krijo një llogari falas për të rezervuar udhëtime dhe për të marrë çmime ekskluzive.',
 'cta_title'=>'Gati për udhëtimin tjetër?','cta_sub'=>'Bashkohu me ne dhe zbulo botën me VentoTravel.',
 'seo_dest_t'=>'Destinacionet – VentoTravel','seo_dest_d'=>'Shfletoni destinacionet tona të përzgjedhura me çmime ekskluzive me zbritje.',
 'dest_title'=>'Destinacionet','dest_sub'=>'Vende të përzgjedhura, çmime ekskluzive.','sort_by'=>'Rendit sipas','sort_low'=>'Çmimi: nga më i lirë','sort_high'=>'Çmimi: nga më i shtrenjtë','sort_new'=>'Më të rejat','sort_old'=>'Më të vjetrat','price'=>'Çmimi','only'=>'Vetëm','book_this'=>'Rezervo këtë udhëtim','no_dest'=>'Nuk ka destinacione në dispozicion për momentin, të lutem provo më vonë!','more'=>'Shfaq më shumë','less'=>'Shfaq më pak','off5'=>'-5%',
 'seo_book_t'=>'Rezervo udhëtimin – VentoTravel','seo_book_d'=>'Rezervoni udhëtimin tuaj të radhës me VentoTravel me pak klikime.',
 'book_title'=>'Rezervo udhëtimin','book_sub'=>'Plotëso të dhënat dhe ne kujdesemi për pjesën tjetër.','full_name'=>'Emri dhe mbiemri','email'=>'Email','phone'=>'Nr. i telefonit','destination'=>'Destinacioni','choose_dest'=>'Zgjidh destinacionin','gender'=>'Gjinia','male'=>'Mashkull','female'=>'Femër','other'=>'Tjetër','address'=>'Adresa','book_btn'=>'Konfirmo rezervimin',
 'ok_booked'=>'Udhëtimi u rezervua me sukses!','err_generic'=>'Diçka shkoi keq, të lutem provo përsëri.','err_dest'=>'Destinacioni i zgjedhur nuk është valid!',
 'seo_fb_t'=>'Feedback – VentoTravel','seo_fb_d'=>'Na tregoni çfarë mendoni për VentoTravel.','fb_title'=>'Feedback-u juaj','fb_sub'=>'I lexojmë të gjitha mesazhet – na ndihmojnë të përmirësohemi.','message'=>'Mesazhi','send'=>'Dërgo','ok_fb'=>'Feedback-u u dorëzua me sukses!',
 'seo_faq_t'=>'FAQ – VentoTravel','seo_faq_d'=>'Përgjigje për pyetjet më të shpeshta rreth VentoTravel.','faq_title'=>'Pyetjet më të shpeshta','faq_sub'=>'Përgjigje të shpejta për pyetjet e zakonshme.','no_faq'=>'Ende nuk ka pyetje.',
 'seo_hist_t'=>'Historiku im – VentoTravel','seo_hist_d'=>'Rezervimet dhe feedback-et tuaja.','hist_title'=>'Historiku juaj','hist_sub'=>'Menaxhoni rezervimet dhe feedback-et tuaja.','bookings'=>'Rezervimet','feedbacks'=>'Feedback-et','no_bookings'=>'Nuk ka rezervime.','no_fb'=>'Nuk ka feedback-e.','booking_id'=>'ID e rezervimit','feedback_id'=>'ID e feedback-ut','date'=>'Data','delete'=>'Fshij','edit'=>'Ndrysho','cancel'=>'Anulo','save'=>'Ruaj ndryshimet','ok_deleted'=>'U fshi me sukses!','confirm_del'=>'Jeni i sigurt që dëshironi ta fshini?','dest_id'=>'ID e destinacionit','user_id'=>'ID e përdoruesit',
 'seo_login_t'=>'Kyçu – VentoTravel','seo_login_d'=>'Kyçu në llogarinë tënde VentoTravel për të rezervuar udhëtime.','login_title'=>'Mirë se u ktheve','login_sub'=>'Kyçu për të vazhduar udhëtimin.','password'=>'Fjalëkalimi','login_btn'=>'Kyçu','no_account'=>'Nuk ke llogari?','signup_link'=>'Regjistrohu','err_login'=>'Email ose fjalëkalimi janë gabim, provo përsëri!',
 'seo_reg_t'=>'Regjistrohu – VentoTravel','seo_reg_d'=>'Krijo llogarinë tënde falas në VentoTravel.','reg_title'=>'Krijo llogari','reg_sub'=>'Zgjat më pak se një minutë.','username'=>'Përdoruesi','confirm_pw'=>'Përsërit fjalëkalimin','reg_btn'=>'Regjistrohu','has_account'=>'Ke llogari?','login_link'=>'Kyçu tani',
 'err_email_taken'=>'Ky email ekziston, provo një tjetër!','err_email_admin'=>'Të lutem provo një email tjetër!','err_user_admin'=>'Të lutem provo një përdorues tjetër!','err_pw'=>'Fjalëkalimet nuk përputhen!','ok_reg'=>'Urime, u regjistruat me sukses!',
 'adm_title'=>'Paneli i administratorit','adm_sub'=>'Menaxho destinacionet, rezervimet, feedback-et dhe FAQ.','adm_manage'=>'Menaxho','title'=>'Titulli','photo_url'=>'URL e fotos','small_desc'=>'Përshkrimi i shkurtër','big_desc'=>'Përshkrimi i gjatë','add'=>'Shto','add_dest'=>'Shto destinacion','edit_dest'=>'Ndrysho destinacionin','list_dest'=>'Të gjitha destinacionet','list_book'=>'Të gjitha rezervimet','list_fb'=>'Të gjitha feedback-et','list_faq'=>'Të gjitha pyetjet','question'=>'Pyetja','answer'=>'Përgjigjja','add_faq'=>'Shto pyetje','edit_faq'=>'Ndrysho pyetjen','edit_book'=>'Ndrysho rezervimin','edit_fb'=>'Ndrysho feedback-un','ok_added'=>'U shtua me sukses!','ok_updated'=>'U ndryshua me sukses!','reservation_date'=>'Data e rezervimit','empty'=>'Ende nuk ka asgjë.',
 'footer_rights'=>'Të gjitha të drejtat e rezervuara.','footer_tag'=>'Udhëto përtej të zakonshmes.','lang_en'=>'English','lang_sq'=>'Shqip','switch_lang'=>'Ndrysho gjuhën','close'=>'Mbyll',
],
];

function t(string $k): string { global $STR, $LANG; return $STR[$LANG][$k] ?? $STR['en'][$k] ?? $k; }
function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function lang_url(string $l): string { $p = $_GET; $p['lang'] = $l; return strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($p); }

$FLASH = null;
function flash(string $msg, string $type = 'ok'): void { global $FLASH; $FLASH = ['msg' => $msg, 'type' => $type]; }

function require_login(): void { if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; } }
function require_admin(): void { if (($_SESSION['username'] ?? '') !== 'admin') { header('Location: login.php'); exit; } }
