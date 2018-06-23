<?php
namespace Application\Service;

interface PluginsServiceInterface 
{
	/**
	 * Gets Plugins by ID
	 *
	 * @param int $id
	 * @return PluginsInterface
	 */
	public function getPluginsById($id);

	/**
	 * Updates Plugins
	 *
	 * @param array $data
	 * @param Where|string|array|\Closure $where
	 * @return bool
	 */
	public function updatePlugins($data, $where);
				
}

