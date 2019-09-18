<h1>Yii 2 Widgets</h1>

Yii2 Widgets provide several useful widgets for Yii2 applications.

For example the LimitedTextarea Widget

```php
kmergen\widgets\LimitedTextarea::widget([
                                        'attribute' => 'body',
                                        'model' => $model,
                                        'form' => $form
                                ]);
```