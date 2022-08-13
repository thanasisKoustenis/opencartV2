<?php

/* default/template/product/testview.twig */
class __TwigTemplate_82132940ed47d0b9bf1bdb7e3a4a3583268d2addcddd45a318c39cf37de01a2d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div>";
        // line 2
        echo (isset($context["example"]) ? $context["example"] : null);
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "default/template/product/testview.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  21 => 2,  19 => 1,);
    }
}
/* <div>*/
/* {{ example }}*/
/* </div>*/
