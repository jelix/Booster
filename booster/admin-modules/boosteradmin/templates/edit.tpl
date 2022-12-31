<h1>{$title|eschtml}</h1>
<h2>{@boosteradmin~admin.item_by@} {zone 'booster~author', array('id' => $item_by)}</h2>

{form $form, $action, array('id'=>$id), 'html', array(
	'widgetsAttributes' => [ 'image' => [ 'baseURI'=> $j_basepath.'images-items/']]
)}

	<table class="jforms-table">

	  {formcontrols}
		<tr><td>{ctrl_label}</td><td>{ctrl_control}</td></tr>
	  {/formcontrols}

	</table>

	<div> {formsubmits}{formsubmit}{/formsubmits}</div>

{/form}

