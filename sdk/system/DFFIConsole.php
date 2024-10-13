<?php
namespace system;

/**
 * Class DFFIConsole
 * @package system
 */
class DFFIConsole
{
	/**
     * @return any
     */
	public function isXTerm() {}
	
	/**
     * @return any
     */
	public function hasColorSupport() {}
	
	/**
     * @return any
     */
	public function getWidth() {}
	
	/**
     * @return any
     */
	public function eraseScreen() {}
	
	/**
     * @return any
     */
	public function eraseLine() {}
	
	/**
     * @return any
	 * @param int $row
	 * @param int $column
     */
	public function cursor($row, $column) {}
	
	/**
     * @return any
     */
	public function reset() {}
	
	/**
     * @return any
     */
	public function enableColors() {}
	
	/**
     * @return any
     */
	public function enableColorsForWindows() {}
}