<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset
        xmlns="https://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="https://www.sitemaps.org/schemas/sitemap/0.9
                https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<?php foreach($list as $row): ?>
        <url>
        <loc><?php echo CHtml::encode($row['loc']); ?></loc>
        <changefreq><?php echo $row['frequency']?></changefreq>
        <priority><?php echo $row['priority'];?></priority>
        </url>
<?php endforeach; ?>

</urlset>