<?php

/**
 * Parse code generation templates.
 * @author andre.fourie
 */
class ReGen_Util_TemplateParser
{
	
	
	/**
	 * Instantiate new Template Parser
	 * @return ReGen_Util_TemplateParser
	 */
	public function __construct() {}
	
	/**
	 * Parse code template file to extract structured code blocks.
	 * @param  string $filePath
	 * @return ReGen_Util_CodeBlock
	 */
	static public function parseFileData($filePath)
	{
		$fileData = file($filePath, FILE_IGNORE_NEW_LINES);
		$stack = array();
		$code = null;
		$context = 'None';
		foreach ($fileData as $line)
		{
			if ('/***' == substr($line, 0, 4))
			{
				#-> New code block.
				is_null($code)
					|| array_push($stack, $code);
				$code = new ReGen_Util_CodeBlock();
				$context = 'Meta';
				continue;
			}
			if (' */' == substr($line, 0, 3))
			{
				#-> End of meta.
				$context = 'Code';
				continue;
			}
			if ('/***/' == substr($line, 0, 5))
			{
				#-> End of code block.
				if (!empty($stack))
				{
					$parent = array_pop($stack);
					$parent->addSubBlock($code);
					$code = $parent;
				}
				else
				{
					return $code;
				}
				$context = 'None';
				continue;
			}
			switch ($context)
			{
				case 'Meta':
					$code->setMeta($line);
					break;
				case 'Code':
					$code->addCode($line);
					break;
			}
		}
		return $code;
	}
	
}

