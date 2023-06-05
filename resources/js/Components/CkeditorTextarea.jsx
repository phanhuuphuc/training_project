import CKEditor from 'ckeditor4-react';

export default function CkeditorTextarea({ setData, content = '' }) {

    const handleEditorChange = (event) => {
        const content = event.editor.getData();
        setData('description', content);
    };

    return (
        <CKEditor
            config={{
            // Add any CKEditor configuration options here
            }}
            data={content}
            onChange={handleEditorChange}
        />
    );
}

