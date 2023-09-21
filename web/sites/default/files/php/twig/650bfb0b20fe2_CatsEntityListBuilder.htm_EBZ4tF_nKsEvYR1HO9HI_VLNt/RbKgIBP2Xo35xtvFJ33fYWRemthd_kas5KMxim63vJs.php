<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/cats_module/templates/CatsEntityListBuilder.html.twig */
class __TwigTemplate_e738f5e416ec3b96b9b81964460b456c extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("core/drupal.dialog.ajax"), "html", null, true);
        echo "
  <table";
        // line 15
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 15, $this->source), "html", null, true);
        echo ">
    <thead class=\"test\">
    <tr>
      <th>";
        // line 18
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Cat name"));
        echo "</th>
      <th>";
        // line 19
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Email"));
        echo "</th>
      <th>";
        // line 20
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Image"));
        echo "</th>
      <th>";
        // line 21
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Created"));
        echo "</th>
      <th>";
        // line 22
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Operations"));
        echo "</th>
    </tr>
    </thead>
    <tbody>
      ";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 27
            echo "        <tr>
          <td>";
            // line 28
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "cat_name", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
            echo "</td>
          <td>";
            // line 29
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "email", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
            echo "</td>
          <td>";
            // line 30
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "cat_image", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
            echo "</td>
          <td>";
            // line 31
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "created", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
            echo "</td>
          <td><a href=\"";
            // line 32
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "operations", [], "any", false, false, true, 32), "edit", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
            echo "\">Edit</a>
            <a href=\"";
            // line 33
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "operations", [], "any", false, false, true, 33), "delete", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
            echo "\" class=\"use-ajax button-delete delete-button-class\" data-dialog-type=\"modal\" data-dialog-options=\"{&quot;width&quot;:800}\">Delete</a>
          </td>
        </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 37
        echo "    </tbody>
  </table>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/cats_module/templates/CatsEntityListBuilder.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  109 => 37,  99 => 33,  95 => 32,  91 => 31,  87 => 30,  83 => 29,  79 => 28,  76 => 27,  72 => 26,  65 => 22,  61 => 21,  57 => 20,  53 => 19,  49 => 18,  43 => 15,  39 => 14,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/cats_module/templates/CatsEntityListBuilder.html.twig", "/var/www/web/modules/custom/cats_module/templates/CatsEntityListBuilder.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 26);
        static $filters = array("escape" => 14, "t" => 18);
        static $functions = array("attach_library" => 14);

        try {
            $this->sandbox->checkSecurity(
                ['for'],
                ['escape', 't'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
