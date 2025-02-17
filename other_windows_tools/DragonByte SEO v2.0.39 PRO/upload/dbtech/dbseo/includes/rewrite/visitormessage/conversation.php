<?php if(!defined('IN_DBSEO')) die('Access denied.');

// #############################################################################
// DBSEO "VisitorMessage_Conversation URL" class

/**
* Lets you construct & lookup custom URLs
*/
class DBSEO_Rewrite_VisitorMessage_Conversation
{
	public static $format = 'MemberProfile_VisitorMessage_Conversation';
	public static $structure = 'converse.php?u=%d&u2=%d';

	/**
	 * Creates a SEO'd URL based on the URL fed
	 *
	 * @param string $url
	 * @param array $data
	 * 
	 * @return string
	 */
	public static function resolveUrl($urlInfo = array(), $structure = NULL)
	{
		if (empty($urlInfo['user_id']) AND isset($urlInfo['user_name']))
		{
			// Grab the user ID
			$urlInfo['user_id'] = DBSEO_Filter::reverseUsername($urlInfo['user_name']);
		}

		if (empty($urlInfo['visitor_id']) AND isset($urlInfo['visitor_name']))
		{
			// Grab the visitor ID
			$urlInfo['visitor_id'] = DBSEO_Filter::reverseUsername($urlInfo['visitor_name']);
		}

		// Return the constructed URL
		return sprintf((is_null($structure) ? self::$structure : $structure), $urlInfo['user_id'], $urlInfo['visitor_id'], $urlInfo['page']);
	}

	/**
	 * Creates a SEO'd URL based on the URL fed
	 *
	 * @param string $url
	 * @param array $data
	 * 
	 * @return string
	 */
	public static function createUrl($data = array(), $format = NULL)
	{
		if (!count(DBSEO::$cache['rawurls']))
		{
			// Ensure we got this kickstarted
			DBSEO::initUrlCache();
		}

		// Prepare the regexp format
		$format 		= explode('_', (is_null($format) ? self::$format : $format), 2);
		$rawFormat 		= DBSEO::$cache['rawurls'][strtolower($format[0])][$format[1]];

		// Init this
		$replace = array();

		$data['userid'] = intval($data['userid'] ? $data['userid'] : $data['u']);
		if ($data['userid'])
		{
			// Grab our user info
			DBSEO::getUserInfo($data['userid']);
			$userInfo = DBSEO::$cache['userinfo'][$data['userid']];
		}
		
		if (!$userInfo['userid'])
		{
			// User didn't exist
			return '';
		}

		// Handle userid and username
		$replace['%user_id%'] 	= $userInfo['userid'];
		$replace['%user_name%'] = DBSEO_Filter::filterText($userInfo['username'], NULL, false, true, true, false);


		$data['userid'] = intval($data['userid2'] ? $data['userid2'] : $data['u2']);
		if ($data['userid'])
		{
			// Grab our user info
			DBSEO::getUserInfo($data['userid']);
			$userInfo = DBSEO::$cache['userinfo'][$data['userid']];
		}
		
		if (!$userInfo['userid'])
		{
			// User didn't exist
			return '';
		}

		// Handle userid and username
		$replace['%visitor_id%'] 	= $userInfo['userid'];
		$replace['%visitor_name%'] 	= DBSEO_Filter::filterText($userInfo['username'], NULL, false, true, true);


		if ($data['page'])
		{
			// We had a paged blog
			$replace['%page%'] = $data['page'];
		}

		// Handle the replacements
		$newUrl = str_replace(array_keys($replace), $replace, $rawFormat);

		//if (strpos($newUrl, '%') !== false)
		//{
			// We should not return true if any single URL remains
			//return '';
		//}

		// Return the new URL
		return $newUrl;
	}
}