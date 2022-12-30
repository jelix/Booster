<h1>{$title}</h1>
<h2><a href="{jurl 'booster~default:viewItem', array('id' => $id, 'name' => $name)}">{$name}</a></h2>

<ul class="modifs">
{foreach $modified as $mod}
    <li>
        <strong>{jlocale 'booster~main.'.$mod->field}</strong> : 
        <span class="old-value">
            {if empty($mod->old_value)}
                " "
            {else}
                {$mod->old_value} 
            {/if}
        </span>
        => 
        <span class="new-value">
            {if empty($mod->new_value)}
                " "
            {else}
                {$mod->new_value} 
            {/if}
        </span>
    </li>
{/foreach}
</ul>


{form $form, $action, array('id'=>$id), 'html', array(
    'widgetsAttributes' => [ 'image' => [ 'baseURI'=> $j_basepath.'images-items/', 'showModeForNewImage'=>'URL']]
)}
    {formsubmit '_submit'}
    <table class="jforms-table">

      {formcontrols}
        <tr><td>{ctrl_label}</td><td>{ctrl_control}</td></tr>
      {/formcontrols}

    </table>

    <div> {formsubmit '_submit'}</div>

{/form}

