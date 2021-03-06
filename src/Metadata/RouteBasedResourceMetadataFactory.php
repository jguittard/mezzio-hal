<?php

/**
 * @see       https://github.com/mezzio/mezzio-hal for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio-hal/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio-hal/blob/master/LICENSE.md New BSD License
 */

namespace Mezzio\Hal\Metadata;

use function array_intersect;
use function array_keys;

class RouteBasedResourceMetadataFactory implements MetadataFactoryInterface
{
    /**
     * Creates a RouteBasedResourceMetadata based on the MetadataMap configuration.
     *
     * @param string $requestedName The requested name of the metadata type.
     * @param array $metadata The metadata should have the following structure:
     *     <code>
     *     [
     *          // Fully qualified class name of the AbstractMetadata type.
     *          '__class__' => RouteBasedResourceMetadata::class,
     *
     *          // Fully qualified class name of the resource class.
     *          'resource_class' => MyResource::class,
     *
     *          // The route to use when generating a self relational link for
     *          // the resource.
     *          'route' => 'my-resouce',
     *
     *          // The extractor/hydrator service to use to extract resource data.
     *          'extractor' => 'MyExtractor',
     *
     *          // Optional params
     *
     *          // What property in the resource represents its identifier.
     *          // Defaults to "id".
     *          'resource_identifier' => 'id',
     *
     *          // What placeholder in the route string represents the resource
     *          // identifier. Defaults to "id".
     *          // Deprecated since 1.4.0; use the 'identifiers_to_placeholders_mapping'
     *          // setting instead.
     *          'route_identifier_placeholder' => 'id',
     *
     *          // An array of additional routing parameters to use when
     *          // generating the self relational link for the collection
     *          // resource. Defaults to an empty array.
     *          'route_params' => [],
     *
     *          // An array mapping resource properties to routing placeholder
     *          // names. This can also be used to replace the
     *          // 'route_identifier_placeholder' setting, which will be removed
     *          // in version 2.0.
     *          'identifiers_to_placeholders_mapping' => ['id' => 'id'],
     *     ]
     *     </code>
     * @return AbstractMetadata
     * @throws Exception\InvalidConfigException
     */
    public function createMetadata(string $requestedName, array $metadata) : AbstractMetadata
    {
        $requiredKeys = [
            'resource_class',
            'route',
            'extractor'
        ];

        if ($requiredKeys !== array_intersect($requiredKeys, array_keys($metadata))) {
            throw Exception\InvalidConfigException::dueToMissingMetadata(
                RouteBasedResourceMetadata::class,
                $requiredKeys
            );
        }

        return new $requestedName(
            $metadata['resource_class'],
            $metadata['route'],
            $metadata['extractor'],
            $metadata['resource_identifier'] ?? 'id',
            $metadata['route_identifier_placeholder'] ?? 'id',
            $metadata['route_params'] ?? [],
            $metadata['identifiers_to_placeholders_mapping'] ?? ['id' => 'id']
        );
    }
}
