import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import {dpRegisterBlockType} from "../../utils/register-blocks";
import metadata from '../../../blocks/listings/block.json';
import attributes from "./attributes";

dpRegisterBlockType(metadata, {
    icon: 'book-alt',
    attributes,
    edit: (props) => {
        return ( 
            <ServerSideRender
                block="directory-plugin/listings"
                attributes={ props.attributes }
            />
        );
    },
    save: (props) => {
        return null;
    }
});