import { __ } from "@wordpress/i18n";
import { registerBlockType } from '@wordpress/blocks';
import metadata from '../../../blocks/listings/block.json';
import attributes from "./attributes";

registerBlockType(
	metadata.name,
	{
		icon: 'book-alt',
		attributes,
		edit: () => {
			return ( < p > Listing Editor page < / p > );
		},
		save: () => {
			return ( < p > Listing Frontend page < / p > );
		}
	}
);
