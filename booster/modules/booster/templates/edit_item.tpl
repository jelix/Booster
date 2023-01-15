<div id="article">
    <h1>{jlocale 'booster~main.item.edit', array($form->getData('name'))}</h1>
    <p>{@booster~main.edit.explain.moderation@}</p>
    {form $form, $action, array('id'=>$id), 'html', array(
    'widgetsAttributes' => [ 'image' => [ 'baseURI'=> $j_basepath.'images-items/']]
    )}
    <table class="jforms-table">
        <tr>
            <th>{ctrl_label 'item_composer_id'}</th>
            <td>{ctrl_control 'item_composer_id'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'name'}</th><td>{ctrl_control 'name'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'type_id'}</th><td>{ctrl_control 'type_id'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'tags'}</th><td>{ctrl_control 'tags'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'dev_status'}</th><td>{ctrl_control 'dev_status'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'slogan'}</th>
            <td>{ctrl_control 'slogan'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'short_desc'}</th>
            <td>{ctrl_control 'short_desc'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'slogan_fr'}</th>
            <td>{ctrl_control 'slogan_fr'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'short_desc_fr'}</th>
            <td>{ctrl_control 'short_desc_fr'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'author'}</th><td>{ctrl_control 'author'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'url_repo'}</th><td>{ctrl_control 'url_repo'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'url_website'}</th><td>{ctrl_control 'url_website'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'url_download'}</th><td>{ctrl_control 'url_download'}</td>
        </tr>
        <tr>
            <th>{ctrl_label 'image'}</th><td>{ctrl_control 'image'}</td>
        </tr>
    </table>
    <div>
        {formsubmit}
    </div>
    {/form}
    {include 'booster~zone.syntax_wiki'}
</div>
