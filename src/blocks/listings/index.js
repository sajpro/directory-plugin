import { __ } from "@wordpress/i18n";
import {dpRegisterBlockType} from "../../utils/register-blocks";
import metadata from '../../../blocks/listings/block.json';
import attributes from "./attributes";

dpRegisterBlockType(metadata, {
    icon: 'book-alt',
    attributes,
    edit: () => {
        return ( <p> Listing Editor page </p > );
    },
    save: () => {
        return ( <p> Listing Frontend page </p> );
    }
});