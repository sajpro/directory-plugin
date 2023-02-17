import React from 'react';
import { BaseControl, Dropdown, Button } from "@wordpress/components";
import CodeMirror from '@uiw/react-codemirror';
import { css } from '@codemirror/lang-css';

export const DpCustomCss = (props) => {
    let { attributes, setAttributes,attrId, label } = props;

    const onChange = React.useCallback((value, viewUpdate) => {
        setAttributes({[attrId]:value})
    }, []);

    return (
        <BaseControl>
            <CodeMirror
                style={{width:'100%'}}
                value={attributes[attrId]}
                height="200px"
                extensions={[css()]}
                onChange={onChange}
            />
        </BaseControl>
    );
}