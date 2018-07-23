<?php
namespace Application\Service;

interface SettingsServiceInterface 
{
	/**
	 * Gets settings by ID
	 *
	 * @param int $id
	 * @return SettingsInterface
	 */
	public function getSettingsById($id);

	/**
	 * Updates Settings
	 *
	 * @param array $data
	 * @param Where|string|array|\Closure $where
	 * @return bool
	 */
	public function updateSettings($data, $where);
				
}

