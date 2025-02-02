<?php
namespace app\controllers;
use yii\base\Controller;

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="This is my website cool API",
 *         description="Api description...",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="antipoff.artyom@gmail.com"
 *         ),
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about my website",
 *         url="http..."
 *     )
 * )
 */
class SwaggerController extends Controller{

}