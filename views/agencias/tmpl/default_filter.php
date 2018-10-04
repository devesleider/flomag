<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */

defined('JPATH_BASE') or die;

$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

// Check if any filter field has been filled
$filters = false;
if (isset($data['view']->filterForm))
{
	$filters = $data['view']->filterForm->getGroup('filter');
}

// Check if there are filters set.
if ($filters !== false)
{
	$filterFields = array_keys($filters);
	$filtered     = false;
	$filled       = false;
	foreach ($filterFields as $filterField)
	{
		$filterField = substr($filterField, 7);
		$filter      = $data['view']->getState('filter.' . $filterField);
		if (!empty($filter))
		{
			$filled = $filter;
		}
		if (!empty($filled))
		{
			$filtered = true;
			break;
		}
	}
}

// Set some basic options
$customOptions = array(
	'filtersHidden'       => isset($data['options']['filtersHidden']) ? $data['options']['filtersHidden'] : empty($data['view']->activeFilters) && !$filtered,
	'defaultLimit'        => isset($data['options']['defaultLimit']) ? $data['options']['defaultLimit'] : JFactory::getApplication()->get('list_limit', 20),
	'searchFieldSelector' => '#filter_search',
	'orderFieldSelector'  => '#list_fullordering'
);

$data['options'] = array_unique(array_merge($customOptions, $data['options']));

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';

// Load search tools
JHtml::_('searchtools.form', $formSelector, $data['options']);
?>

<div class="js-stools clearfix">
	<div class="clearfix">
		<div class="js-stools-container-bar">
			<label for="filter_search" class="element-invisible"
				aria-invalid="false"><?php echo JText::_('COM_FLOTA_SEARCH_FILTER_SUBMIT'); ?></label>

			<div class="btn-wrapper input-append">
				<?php echo $filters['filter_search']->input; ?>
				<button type="submit" class="btn hasTooltip" title=""
					data-original-title="<?php echo JText::_('COM_FLOTA_SEARCH_FILTER_SUBMIT'); ?>">
					<i class="icon-search"></i>
				</button>
			</div>
			<?php if ($filters): ?>
				<div class="btn-wrapper hidden-phone">
					<button type="button" class="btn hasTooltip js-stools-btn-filter" title=""
						data-original-title="<?php echo JText::_('COM_FLOTA_SEARCH_TOOLS_DESC'); ?>">
						<?php echo JText::_('COM_FLOTA_SEARCH_TOOLS'); ?> <i class="caret"></i>
					</button>
				</div>
			<?php endif; ?>

			<div class="btn-wrapper">
				<button type="button" class="btn hasTooltip js-stools-btn-clear" title=""
					data-original-title="<?php echo JText::_('COM_FLOTA_SEARCH_FILTER_CLEAR'); ?>"
					onclick="javascript:jQuery(this).closest('form').find('input').val('');">
					<?php echo JText::_('COM_FLOTA_SEARCH_FILTER_CLEAR'); ?>
				</button>
			</div>
		</div>
	</div>
	<!-- Filters div -->
	<div class="js-stools-container-filters hidden-phone clearfix" style="">
		<?php // Load the form filters ?>
		<?php if ($filters) : ?>
			<?php foreach ($filters as $fieldName => $field) : ?>
				<?php if ($fieldName != 'filter_search') : ?>
					<div class="js-stools-field-filter">
						<?php echo $field->input; ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>