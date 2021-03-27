<?php

/**
 * Truncates `CONTENT` and normalizes slashes to `/`.
 */
function cleanPath(SplFileInfo $path): string {
  $path = $path->getPathname();
  $path = str_replace(CONTENT, '', $path);
  $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
  return $path;
}

/**
 * Maps an iterable to an array
 */
function mapToArray(iterable $iterable, callable $function): array {
  return iterator_to_array(map($iterable, $function));
}

/**
 * Returns an array containing only the whitelisted keys.
 */
function array_whitelist(array $array, array $whitelist)
{
	return array_intersect_key($array, array_flip($whitelist));
}

/**
 * Returns an array containing only the blaclisted keys.
 */
function array_blacklist(array $array, array $blacklist)
{
    return array_diff_key($array, array_flip($blacklist));
}

/**
 * @see https://github.com/nikic/iter/blob/master/src/iter.php#L79-L83
 */
function map(iterable $iterable, callable $function): Iterator {
  foreach ($iterable as $key => $value)
    yield $function($value, $key);
}

/**
 * @see https://github.com/nikic/iter/blob/master/src/iter.php#L320-L326
 */
function reduce(iterable $iterable, $startValue = null, callable $function) {
  $acc = $startValue;
  foreach ($iterable as $key => $value) {
      $acc = $function($acc, $value, $key);
  }
  return $acc;
}
