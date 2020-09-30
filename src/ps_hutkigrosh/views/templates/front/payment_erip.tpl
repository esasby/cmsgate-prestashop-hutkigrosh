{extends "$layout"}

{block name="content"}
  <section>
    {$completionPanel->render()}
{*    <?php $_smarty_tpl->tpl_vars['completionPanel']->value->render(); ?>*}
  </section>
{/block}
