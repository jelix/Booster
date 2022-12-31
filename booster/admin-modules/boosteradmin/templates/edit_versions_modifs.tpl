<h1>{$title}</h1>
<h2>{@boosteradmin~admin.item_by@} {$item_by}</h2>

<p><a href="{jurl 'booster~default:viewItem', array('id' => $item_id, 'name' => $item_name)}">{$item_name|eschtml}</a>

<ul class="modifs">
{foreach $modified as $mod}
    <li>
        <strong>{jlocale 'booster~main.'.$mod->field}</strong> : 
        <span class="old-value">
            {if empty($mod->old_value)}
                " "
            {else}
                {$mod->old_value|eschtml}
            {/if}
        </span>
        => 
        <span class="new-value">
            {if empty($mod->new_value)}
                " "
            {else}
                {$mod->new_value|eschtml}
            {/if}
        </span>
    </li>
{/foreach}
</ul>


{form $form, $action, array('id'=>$id)}
    <table class="jforms-table">

      {formcontrols}
            <tr><td>{ctrl_label}</td><td>{ctrl_control}</td></tr>
      {/formcontrols}

    </table>

    <div> {formsubmit '_submit'}</div>

{/form}

