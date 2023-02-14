import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';
import {dpRegisterBlockType} from "../../utils/register-blocks";
import metadata from '../../../blocks/listings/block.json';
import attributes from "./attributes";

dpRegisterBlockType(metadata, {
    icon: 'book-alt',
    attributes,
    edit: ({attributes}) => {
        console.log({attributes});
        const blockProps = useBlockProps();
        return (
            <div { ...blockProps }>
                <ServerSideRender
                    block="directory-plugin/listings"
                    attributes={ attributes }
                />
            </div>
        );
    },
    save: (props) => {
        return null;
    }
});