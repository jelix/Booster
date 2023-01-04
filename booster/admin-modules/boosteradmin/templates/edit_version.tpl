<h1>{$title|eschtml}</h1>

{if $modified}
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
{/if}

{form $form, $action, $actionParams}
    <table class="jforms-table">

      {formcontrols}
            <tr><td>{ctrl_label}</td><td>{ctrl_control}</td></tr>
      {/formcontrols}

    </table>

    <div> {formsubmits}{formsubmit}{/formsubmits}</div>

{/form}

<p><a href="{jurl 'boosteradmin~items:view', array('id' => $item_id)}">{@boosteradmin~admin.back.to.project@}</a></p>
