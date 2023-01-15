{meta_html js $j_basepath.'booster/js/itemform.js'}

<h1>{$title|eschtml}</h1>

{if $id === null}
    {assign $submitParameters= []}
{else}
    {assign $submitParameters = ['id'=>$id, $offsetParameterName=>$page]}
{/if}

{form $form, $submitAction, $submitParameters, 'html', array(
'widgetsAttributes' => [ 'image' => [ 'baseURI'=> $j_basepath.'images-items/']]
)}
    <table class="jforms-table">
        {formcontrols}
            {ifctrl 'item_composer_id'}
            <tr>
                <th scope="row">{ctrl_label}
                </th>
                <td>{ctrl_control}
                    <span id="checkcomposerresult"
                          data-url="{jurl 'boosteradmin~items:checkcomposer'}"></span>
                </td>
            </tr>
            {else}
            <tr>
                <th scope="row">{ctrl_label}
                </th>
                <td>{ctrl_control}
                </td>
            </tr>
            {/ifctrl}
        {/formcontrols}
    </table>
    <div>{formsubmits}{formsubmit}{/formsubmits}</div>
{/form}

<p><a href="{jurl $listAction, array($offsetParameterName=>$page)}" class="crud-link">{@jelix~crud.link.return.to.list@}</a>.</p>