<?php if (($data['RelatedProductsPro']['Enabled'] != 'no') && ($RelatedProductsProShowModule=="yes")) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $currenttemplate?>/stylesheet/relatedproductspro.css" />

    <?php if(!empty($data['RelatedProductsPro']['CustomCSS'])): ?>
    <style>
    <?php echo htmlspecialchars_decode($data['RelatedProductsPro']['CustomCSS']); ?>
    </style>
    <?php endif; ?>

	<?php if ($data['RelatedProductsPro']['WrapInWidget'] != 'no') { ?>
    	<div class="box box-relatedproductspro">
		 	 <div class="box-heading"><?php echo $data['RelatedProductsPro']['WidgetTitle']; ?></div>
			  <div class="box-content">
           <?php } else { ?>
           <strong><?php echo $data['RelatedProductsPro']['WidgetTitle']; ?></strong><br /><br />
           <?php } ?>
            <div class="box-product">
              <?php foreach ($products as $product) { ?>
              <div>
                <?php if ($product['thumb']) { ?>
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                <?php } ?>
                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($product['price']) { ?>
                <div class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                  <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
                <?php } ?>
				<?php if ($data['RelatedProductsPro']['AddToCart'] == 'yes') { ?>
                <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
               <?php } ?>
              </div>
              <?php } ?>
            </div>
			<?php if ($data['RelatedProductsPro']['WrapInWidget'] != 'no') { ?>
         	 </div>
			</div> 
             <?php } ?>

<?php } ?>