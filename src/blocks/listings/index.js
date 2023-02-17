import {dpRegisterBlockType} from "../../utils/register-blocks";
import metadata from '../../../blocks/listings/block.json';
// import attributes from "./attributes";
import Edit from "./edit";
console.log(metadata.attributes);
dpRegisterBlockType(metadata, {
    icon: 'book-alt',
    
    attributes: metadata.attributes,

    edit: Edit,
    save: (props) => {
        return null;
    }
});