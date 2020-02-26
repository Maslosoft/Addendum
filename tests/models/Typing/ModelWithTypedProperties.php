<?php


namespace Maslosoft\AddendumTest\Models\Typing;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

class ModelWithTypedProperties implements AnnotatedInterface
{
	/**
	 * @Label('Title')
	 * @var string
	 */
	public string $title = 'TEST';

	/**
	 * @Label('Items')
	 * @var string
	 */
	public array $items = [1,2,3];

	/**
	 * @Label('Items2')
	 * @var string
	 */
	public ?array $items2 = [1];


	/**
	 * @Label('Description')
	 * @var string|null
	 */
	public ?string $description = 'TEST2';

	/**
	 * @Label('User')
	 * @var AnnotatedInterface
	 */
	public AnnotatedInterface $user;

	/**
	 * @Label('Company')
	 * @var AnnotatedInterface|null
	 */
	public ?AnnotatedInterface $company;
}