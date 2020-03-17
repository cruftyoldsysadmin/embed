<?php
/**
 * Content for the embedded job listing.
 *
 * This template can be overridden by copying it to yourtheme/wp-job-manager-embeddable-job-widget/content-embeddable-widget-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager - Embeddable Job Widget
 * @category    Template
 * @version     1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="embeddable-job-widget-listing">
	<a href="<?php the_job_permalink(); ?>" target="_blank">
		<div class="embeddable-job-widget-listing-title">
			<?php the_title(); ?>
		</div>
		<div class="embeddable-job-widget-listing-meta">
			<?php
				$meta = array();

				if ( $data = get_the_job_type() ) {
					$meta[] = '<span class="embeddable-job-widget-listing-job-type">' . $data->name . '</span>';
				}
				if ( $data = get_the_job_location() ) {
					$meta[] = '<span class="embeddable-job-widget-listing-job-location">' . $data . '</span>';
				}
				if ( $data = get_the_company_name() ) {
					$meta[] = '<span class="embeddable-job-widget-listing-job-company">' . $data . '</span>';
				}

				echo implode( ' - ', $meta );
			?>
		</div>
	</a>
</li>