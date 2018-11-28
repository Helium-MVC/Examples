<?php header("Content-Type: application/rss+xml; charset=ISO-8859-1"); ?>
<?php echo '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n"; ?>
<rss version="2.0">
	<channel>
		<title>Site 1 RSS Feed</title>
		<link><?php PVConfiguration::getConfiguration('sites') -> site1; ?></link>
		<description>This is an example RSS feed</description>
		<language>en-us</language>
		<copyright>Copyright (C) 2018 he2mvc.com</copyright>
		<?php foreach($posts as $post): ?>
		<item>
			<title><?= $post -> title; ?></title>
			<description><?= PVTools::truncateText($post -> content, 200); ?></description>
			<link><?php PVConfiguration::getConfiguration('sites') -> site1; ?>/posts/view/<?= $post -> post_id; ?></link>
			<pubDate><?= $this -> Format -> dateTime($post -> date_created, 'D, d M Y H:i:s T'); ?></pubDate>
		</item>
        <?php endforeach; ?>
</channel>
</rss>