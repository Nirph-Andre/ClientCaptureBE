<?php

/**
 * Object format of parsed code generation template.
 * @author andre.fourie
 */
class ReGen_Util_CodeBlock
{

	/**
	 * @var string
	 */
	private $_type = '';

	/**
	 * @var string
	 */
	private $_id = '';

	/**
	 * @var array
	 */
	private $_requiredTags = array();

	/**
	 * @var string
	 */
	private $_state = 'include';

	/**
	 * @var array
	 */
	private $_config = array();

	/**
	 * @var string
	 */
	private $_code = '';

	/**
	 * @var array
	 */
	private $_codeBlocks = array();


	/**
	 * Retrieve Type for this code block.
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Retrieve ID for this code block.
	 * @return string
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Retrieve tags for this code block.
	 * @return string
	 */
	public function getTags()
	{
		return $this->_requiredTags;
	}

	/**
	 * Retrieve inclusion state for this code block.
	 * @return string
	 */
	public function getState()
	{
		return $this->_state;
	}

	/**
	 * Retrieve inclusion state for this code block.
	 * @return string
	 */
	public function setState($state)
	{
		$this->_state = $state;
	}

	/**
	 * Retrieve config for this code block.
	 * @return string
	 */
	public function getConfig()
	{
		return $this->_config;
	}

	/**
	 * Publish code for this code block.
	 * @param  string $codeBlockId
	 * @param  array  $tags
	 * @return string
	 */
	public function publish(array $tags = array())
	{
		#-> Prepare code block.
		$id = $this->getId();
		$requiredTags = $this->getTags();
		$code = $this->_code;
		foreach ($requiredTags as $tag)
		{
			if (!isset($tags[$tag]))
			{
				throw new Zend_Exception("ReGen_Util_CodeBlock::publish on $id requires tag $tag.");
			}
			$code = str_replace(
					array(
							'__' . $tag . '__',
							"/*$tag*/",
							"\t\t/*** $tag ***/"
							),
					$tags[$tag],
					$code
					);
		}
		
		#-> Prepare sub code blocks.
		foreach ($tags as $tag => $value)
		{
			if (!isset($requiredTags[$tag]))
			{
				$code = str_replace(array("\t\t/*** $tag ***/"), $value, $code);
			}
		}
		
		#-> Done
		return $code;
	}
	
	/**
	 * Inject sub code into published code block.
	 * @param  string $code
	 * @param  array  $codeTags
	 * @return string
	 */
	public function injectSubCode($code, array $codeTags)
	{
		foreach ($codeTags as $tag => $value)
		{
			$code = str_replace(array("\t\t/*** $tag ***/"), $value, $code);
		}
		return $code;
	}

	/**
	 * Check if we have sub code block by id.
	 * @return string
	 */
	public function haveCodeBlock($blockId)
	{
		return !isset($this->_codeBlocks[$blockId])
			? false
			: true;
	}

	/**
	 * Retrieve sub code block by id.
	 * @return ReGen_Util_CodeBlock
	 */
	public function getCodeBlock($blockId)
	{
		if (!isset($this->_codeBlocks[$blockId]))
		{
			throw new Zend_Exception("ReGen_Util_CodeBlock::getCodeBlock on $this->_id does not have sub code block $blockId.");
		}
		return $this->_codeBlocks[$blockId];
	}

	/**
	 * Retrieve sub code blocks for this code block.
	 * @return string
	 */
	public function getCodeBlocks()
	{
		return $this->_codeBlocks;
	}

	/**
	 * Retrieve sub code block id's for this code block.
	 * @return string
	 */
	public function getCodeBlockIds()
	{
		$blocks = array();
		foreach ($this->_codeBlocks as $codeBlock)
		{
			$blocks[$codeBlock->getId()] = $codeBlock->getType();
		}
		return $blocks;
	}

	/**
	 * Set meta data for this code block.
	 * @param string $input
	 */
	public function setMeta($input)
	{
		if (false !== strpos($input, '@'))
		{
			list($pre, $spec) = explode('@', $input);
			list($param, $value) = explode(':', $spec);
			$value = str_replace(' ', '', $value);
			switch ($param)
			{
				case 'type':
					$this->_type = $value;
					break;
				case 'id':
					$this->_id = $value;
					break;
				case 'requiredTags':
					$this->_requiredTags = explode(', ', $value);
					break;
				case 'defaultState':
					$this->_state = $value;
					break;
				default:
					$this->_config = explode(', ', $value);
					break;
			}
		}
	}

	/**
	 * Add a line of code to the code block.
	 * @param string $line
	 */
	public function addCode($line)
	{
		$this->_code .= $line . "\n";
	}

	/**
	 * Add a sub code block to this one.
	 * @param ReGen_Util_CodeBlock $codeBlock
	 */
	public function addSubBlock(ReGen_Util_CodeBlock $codeBlock)
	{
		$id = $codeBlock->getId();
		$this->addCode("\t\t/*** $id ***/");
		$this->_codeBlocks[$id] = $codeBlock;
	}

}
