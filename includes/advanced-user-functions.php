<?php

require_once(ADVANCED_USER_DIR . 'includes/advanced-user-encrypted.php');
require_once(ADVANCED_USER_DIR . 'includes/advanced-user-decrypted.php');

/* добавляем поля в форму регистрации в WordPress */
add_action('register_form','show_fields');
function show_fields() {
?>
<p>
	<label>Адрес</label>
	<input id="address" class="input" type="text" value="" name="address" />
</p>
<p>
	<label>Номер телефона</label>
	<input id="phone" type="text" name="mobile" class="input">
</p>
<p>
	<label>Пол</label>
  <p><input name="gender" type="radio" value="Мужской" checked> Мужской</p>
  <p><input name="gender" type="radio" value="Женский"> Женский</p>
</p>
<p>
	<label>Семейное положение</label>
  <p><input name="status" type="radio" value="Не замужем, не женат" checked> Не замужем, не женат</p>
  <p><input name="status" type="radio" value="Замужем, женат"> Замужем, женат</p>
</p>
<?php }

/* Проверка полей на валидность данных */
add_action('register_post','check_fields',10,3);
function check_fields ( $login, $email, $errors ) {
	global $address, $mobile, $gender, $status;

	if ($_POST['address'] == '' || strlen($_POST['address']) <= 6){
		$errors->add( 'empty_realname', "ОШИБКА: Адрес?" );
	} else {
		$address = $_POST['address'];
	}

	if ($_POST['mobile'] == ''){
		$errors->add( 'empty_realname', "ОШИБКА: Номер телефона?" );
	} else {
		$mobile = $_POST['mobile'];
	}

  if ($_POST['gender'] == ''){
		$errors->add( 'empty_realname', "ОШИБКА: Укажите пол!" );
	} else {
		$gender = $_POST['gender'];
	}

  if ($_POST['status'] == ''){
		$errors->add( 'empty_realname', "ОШИБКА: Укажите семейное положение!" );
	} else {
		$status = $_POST['status'];
	}
	return $errors;
}

/* Функция добавления метаполей */
add_action('user_register', 'register_fields');
function register_fields($user_id, $password= "", $meta=array()){
	update_user_meta( $user_id, 'address', encrypted_decrypted($_POST['address'], $user_id, $key = 'address'));
  update_user_meta( $user_id, 'phone', encrypted_decrypted($_POST['mobile'], $user_id, $key = 'phone'));
  update_user_meta( $user_id, 'gender', encrypted_decrypted($_POST['gender'], $user_id, $key = 'gender'));
	update_user_meta( $user_id, 'family-status', encrypted_decrypted($_POST['status'], $user_id, $key = 'family-status'));

}

/* Функция вывода списка авторов */
function authors_list(){
  $number     = 3;
  $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $offset     = ($paged - 1) * $number;
  $users      = get_users();
  $query      = get_users('&offset='.$offset.'&number='.$number);
  $total_users = count($users);
  $total_query = count($query);
  $total_pages = intval($total_users / $number + 1);

  echo '<ul id="users">';

  foreach($query as $q) { ?>
    <li class="user clearfix">
        <div class="user-avatar">
					<a href="<?php echo get_author_posts_url($q->ID);?>">
            <?php echo get_avatar( $q->ID, 80 ); ?>
					</a>
        </div>
        <div class="user-data">

            <h4 class="user-name">
                <a href="<?php echo get_author_posts_url($q->ID);?>">
                    <?php echo get_the_author_meta('display_name', $q->ID);?>
                </a>
            </h4>
        </div>
    </li>

  <?php }

  echo '</ul>';

  if ($total_users > $total_query) {
  echo '<div id="pagination" class="clearfix">';
  echo '<p class="pages">Страницы:</p>';
    $current_page = max(1, get_query_var('paged'));
    echo paginate_links(array(
          'base' => get_pagenum_link(1) . '%_%',
          'format' => 'page/%#%/',
          'current' => $current_page,
          'total' => $total_pages,
          'prev_next'    => false,
          'type'         => 'list',
      ));
  echo '</div>';
}
}

/* Функция создания шорткода */
add_shortcode('advanced-user', 'generate_shortcode');
function generate_shortcode( $atts ) {
  return authors_list();
	# результат [advanced-user]
}


/* Подключение кастомного шаблона author.php */
add_filter('template_include', 'advanced_user_author');
function advanced_user_author($template){
  if (is_author()){
    if ($new_template = ADVANCED_USER_DIR . 'templates/advanced-user-author.php'){
      return $new_template;
    }
  }
  return $template;
}
