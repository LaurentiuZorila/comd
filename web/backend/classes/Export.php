<?php
class Export
{
    public function actionExport()
    {
        $fileName = security()->generateRandomString(8) . '.csv';
        $fileName = get_alias('@common/runtime/' . $fileName);

        if (!($fp = @fopen($fileName, 'w'))) {
            notify()->addError(t('app', 'Unable to access the output for writing the data!'));
            return $this->redirect(['index']);
        }

        $model = new Snippet();
        fputcsv($fp, [
            $model->getAttributeLabel('status_id'),
            $model->getAttributeLabel('title'),
            $model->getAttributeLabel('require_password'),
            $model->getAttributeLabel('password_hint'),
            $model->getAttributeLabel('shred_when'),
            $model->getAttributeLabel('shred_later_at'),
            $model->getAttributeLabel('created_at'),
        ]);

        $models = Snippet::find()->where(['customer_id' => (int)customer()->id]);
        foreach ($models->each(100) as $model) {
            fputcsv($fp, [
                $model->statusName,
                $model->title,
                array_get($model->getRequirePasswordList(), $model->require_password),
                $model->password_hint,
                $model->shred_when,
                $model->shred_later_at ? app()->formatter->asDateTime($model->shred_later_at) : '',
                app()->formatter->asDatetime($model->created_at)
            ]);
        }

        fclose($fp);

        /* Make sure we remove the created file */
        app()->on(Application::EVENT_AFTER_REQUEST, function() use ($fileName) {
            if (is_file($fileName)) {
                unlink($fileName);
            }
        });

        return response()->sendStreamAsFile(fopen($fileName, 'r'), 'snippets.csv');
    }
}