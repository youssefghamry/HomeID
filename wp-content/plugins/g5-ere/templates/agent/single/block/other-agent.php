<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$query_args = G5ERE()->query_agent()->get_other_agent_query_args();
G5CORE()->query()->query_posts($query_args);
if (!G5CORE()->query()->have_posts()) {
	G5CORE()->query()->reset_query();
	return;
}
?>
<div class="g5ere__single-block g5ere__agent-block g5ere__agent-block-other-agent card">
	<div class="card-header">
		<h2><?php echo esc_html__( 'Other Agent', 'g5-ere' ) ?></h2>
	</div>
	<div class="card-body">
		<?php G5ERE()->get_template( 'agent/single/block/data/other-agent.php' ) ?>
	</div>
</div>
