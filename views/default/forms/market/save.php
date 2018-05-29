<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne, RiverVanRain
 * @copyright slyhne 2010-2015, wZm 2k17
 * @link https://wzm.me
 * @version 2.2
 */
$post = get_entity($vars['guid']);
if ($post) {
	$tu = $post->time_updated;
	$images = unserialize($post->images);
        $postguid = $post->guid;
}else{
    $postguid = -1;
}

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$currency = elgg_get_plugin_setting('market_currency', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
$marketcategories = string_to_tag_array(elgg_get_plugin_setting('market_categories', 'market'));
$custom_choices = string_to_tag_array(elgg_get_plugin_setting('market_custom_choices', 'market'));

echo '<div>';
echo '<label>' . elgg_echo('title') . '</label><span class="elgg-subtext mlm">' . elgg_echo('market:title:help') . '</span>';
echo elgg_view("input/text", array(
				'name' => 'title',
				'value' => $vars['title'],
				'autofocus' => true,
				'required' => true,
				));
echo '</div>';

if (!empty($marketcategories)) {
	$options = array();
	$options[''] = elgg_echo("market:choose");
	foreach ($marketcategories as $option) {
		$options[$option] = elgg_echo("market:category:{$option}");
	}		
	unset($options['all']);
	
	echo '<div>';
	echo '<label>' . elgg_echo('market:categories:choose') . '</label>';
	echo elgg_view('input/dropdown',array(
						'options_values' => $options,
						'value' => $vars['marketcategory'],
						'name' => 'marketcategory',
						'class' => 'mls',
						));
	echo '</div>';
}

$types = array('buy', 'sell', 'swap', 'free');
$options = array();
$options[''] = elgg_echo("market:choose");
foreach ($types as $type) {
	$options[$type] = elgg_echo("market:type:{$type}");
}		
echo '<div>';
echo '<label>' . elgg_echo('market:type:choose') . '</label>';
echo elgg_view('input/dropdown',array(
				'options_values' => $options,
				'value' => $vars['market_type'],
				'name' => 'market_type',
				'id' => 'market-type',
				'class' => 'mls',
				));
echo '</div>';

if (elgg_get_plugin_setting('market_custom', 'market') == 'yes' && !empty($custom_choices)) {
	$custom_options = array();
	$custom_options[''] = elgg_echo("market:choose");
	foreach ($custom_choices as $custom_choice) {
		$custom_options[$custom_choice] = elgg_echo("market:custom:{$custom_choice}");
	}		
	
	echo '<div>';
	echo '<label>' . elgg_echo('market:custom:select') . '</label>';
	echo elgg_view('input/dropdown',array(
						'options_values' => $custom_options,
						'value' => $vars['custom'],
						'name' => 'custom',
						'class' => 'mls',
						));
	echo '</div>';
}

if (elgg_get_plugin_setting('location', 'market') == 'yes') {
	echo "<div><label>";
	echo elgg_echo('market:location') . "</label><span class='elgg-subtext mlm'>" . elgg_echo("market:location:help") . "</span>";
	echo elgg_view("input/location", array(
				"name" => "location",
				"value" => $vars['location'],
				));
	echo '</div>';
}

echo "<div><label>" . elgg_echo("market:text") . "</label>";

if ($allowhtml != 'yes') {
	echo "<span class='elgg-subtext mlm'>" . elgg_echo("market:text:help", array($numchars)) . "</span>";
	
	$counter = '<div class="market_characters_remaining" data-counter>';
	$counter .= '<span data-counter-indicator class="market_charleft">';
	$counter .= $numchars;
	$counter .= '</span>';
	$counter .= elgg_echo('market:charleft');
	$counter .= '</div>';
	
	echo $counter;
	
	echo elgg_view("input/plaintext", array(
			'name' => 'description',
			'value' => $vars['description'],
			'id' => 'plaintext-description', 
			'data-limit' => $numchars,
			'required' => true,
		));
} else {
	echo elgg_view("input/longtext", array(
					'name' => 'description',
					'value' => $vars['description'],
					'required' => true,
					));
}
echo '</div>';

echo "<div><label>" . elgg_echo("market:price") . "</label>";
echo "<span class='elgg-subtext mlm'>" . elgg_echo("market:price:help", array($currency)) . "</span>";
echo elgg_view("input/text", array(
				"name" => "price",
				"value" => $vars['price'],
				"id" => "market-price",
				));
			
echo '</div>';

echo "<div><label>" . elgg_echo("market:tags") . "</label>";
echo "<span class='elgg-subtext mlm'>" . elgg_echo("market:tags:help") . "</span>";
echo elgg_view("input/tags", array(
				"name" => "tags",
				"value" => $vars['tags'],
				));
echo '</div>';

echo '<div><label>'.elgg_echo('market:uploadimages').'</label><span class="elgg-subtext mlm">'.elgg_echo("market:imagelimitation").'</span></div>';

$image1 = elgg_view('market/thumbnail', array(
			'guid' => $postguid,
			'imagenum' => 1,
			'size' => 'medium',
			'class' => 'market-form-image',
			'tu' => $tu
			));
$body1 = "<div><label>" . elgg_echo("market:uploadimage1") . "</label>";
$body1 .= elgg_view("input/file",array('name' => 'upload1'));
if ($images[1]) {
	$body1 .= elgg_view('output/url', array(
			'href' => "action/market/delete_img?guid={$postguid}&img=1",
			'text' => elgg_echo('delete'),
			'is_action' => true,
			'class' => 'elgg-button elgg-button-delete mts',
			'data-confirm' => elgg_echo('market:delete:image'),
			));
}
$body1 .= '</div>';

echo elgg_view_image_block($image1, $body1);

$image2 = elgg_view('market/thumbnail', array(
			'guid' => $postguid,
			'imagenum' => 2,
			'size' => 'medium',
			'class' => 'market-form-image',
			'tu' => $tu
			));
$body2 = "<div><label>" . elgg_echo("market:uploadimage2") . "</label>";
$body2 .= elgg_view("input/file",array('name' => 'upload2'));
if ($images[2]) {
	$body2 .= elgg_view('output/url', array(
			'href' => "action/market/delete_img?guid={$postguid}&img=2",
			'text' => elgg_echo('delete'),
			'is_action' => true,
			'class' => 'elgg-button elgg-button-delete mts',
			'data-confirm' => elgg_echo('market:delete:image'),
			));
}

$body2 .= '</div>';

echo elgg_view_image_block($image2, $body2);

$image3 = elgg_view('market/thumbnail', array(
			'guid' => $postguid,
			'imagenum' => 3,
			'size' => 'medium',
			'class' => 'market-form-image',
			'tu' => $tu
			));
$body3 = "<div><label>" . elgg_echo("market:uploadimage3") . "</label>";
$body3 .= elgg_view("input/file",array('name' => 'upload3'));
if ($images[3]) {
	$body3 .= elgg_view('output/url', array(
			'href' => "action/market/delete_img?guid={$postguid}&img=3",
			'text' => elgg_echo('delete'),
			'is_action' => true,
			'class' => 'elgg-button elgg-button-delete mts',
			'data-confirm' => elgg_echo('market:delete:image'),
			));
}
$body3 .= '</div>';

echo elgg_view_image_block($image3, $body3);

$image4 = elgg_view('market/thumbnail', array(
			'guid' => $postguid,
			'imagenum' => 4,
			'size' => 'medium',
			'class' => 'market-form-image',
			'tu' => $tu
			));
$body4 = "<div><label>" . elgg_echo("market:uploadimage4") . "</label>";
$body4 .= elgg_view("input/file",array('name' => 'upload4'));
if ($images[4]) {
	$body4 .= elgg_view('output/url', array(
			'href' => "action/market/delete_img?guid={$postguid}&img=4",
			'text' => elgg_echo('delete'),
			'is_action' => true,
			'class' => 'elgg-button elgg-button-delete mts',
			'data-confirm' => elgg_echo('market:delete:image'),
			));
}
$body4 .= '</div>';

echo elgg_view_image_block($image4, $body4);

echo '<div>';
echo "<label>" . elgg_echo('access') . "</label><span class='elgg-subtext mlm'>" . elgg_echo("market:access:help") . "</span>";
echo elgg_view('input/access', array('name' => 'access_id', 'class' => 'mrs', 'value' => $vars['access_id']));
echo '</div>';

echo '<div>';
// Terms checkbox and link
$termslink = elgg_view('output/url', array(
			'href' => 'market/terms',
			'text' => elgg_echo('market:terms:title'),
			'class' => 'elgg-lightbox',
			));
$termsaccept = elgg_echo("market:accept:terms", array($termslink));
echo '</div>';
echo "<input type='checkbox' name='accept_terms'><label>{$termsaccept}</label></p>";

echo "<div class='elgg-foot'>";
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
echo '</div>';

