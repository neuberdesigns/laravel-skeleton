<?php
abstract class HierarchyInterface extends Eloquent {
	/**
	 * Filter tree by ID
	 * @param  [type] $name Node ID
	 */
	abstract public function scopeById($query, $id);
	
	/**
	 * Filter tree by Name
	 * @param  [type] $name Node name
	 */
	abstract public function scopeByName($query, $name);
	
	/**
	 * Base scope to get hierarchical data
	 * Without this scope the other querys may fail
	 */
	abstract public function scopeHierarchical($query);
	
	/**
	 * List categories with his Depth
	 * 
	 * Cannot be used with byId() or byName()
	 */
	abstract public function scopeWithDepth($query);
	
	/**
	 * List categories with a depth separator
	 * 
	 * Cannot be used with byId() or byName()
	 * 
	 * @param string $separator String to concat with every level. Default -
	 */
	abstract public function scopeConcat($query, $separator='-');
	
	/**
	 * Add a node after the $parentNode, in the same level.
	 * 
	 * @param integer $parentNode Target node
	 * @param integer $nodeId The node to be inserted after.
	 */
	abstract public function addNext($parentNode, $nodeId);
	
	/**
	 * Add a as a child of another node.
	 * 
	 * The node is insserted in the end of the list
	 * 
	 * @param  integer $parentNode ID of the node to insert in
	 * @param  integer $nodeId ID of the node to be inserted in new position
	 */
	abstract public function appendTo($parentNode, $nodeId);
	
	/**
	 * Delete a node
	 * @param  integer $id ID of the node to delete
	 * @return boolean
	 */
	abstract public function deleteNode($id);
}

