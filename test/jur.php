<?php
	require(__DIR__ . '/../vendor/autoload.php');

	describe('JUR', function() {
		it('should return an instance of JUR when instanciating', function() {
			expect(jur())->toBe()->anInstanceOf('Khalyomede\Jur');
		});

		it('should return an instance of JUR when setting the issued time', function() {
			expect(jur()->issued())->toBe()->anInstanceof('Khalyomede\Jur');
		});

		it('should return an instance of JUR when setting the message', function() {
			expect(jur()->message('foo'))->toBe()->anInstanceOf('Khalyomede\Jur');
		});

		it('should return an instance of JUR when setting the data', function() {
			expect(jur()->data('foo'))->toBe()->anInstanceOf('Khalyomede\Jur');
		});

		it('should retun an instance of JUR when setting the request type', function() {
			expect(jur()->request('get'))->toBe()->anInstanceOf('Khalyomede\Jur');
		});

		it('should return an instance of JUR when setting the resolved time', function() {
			expect(jur()->resolved())->toBe()->anInstanceOf('Khalyomede\Jur');
		});

		it('should throw an InvalidArgumentException if the request type is not supported', function() {
			expect(function() {
				jur()->request('foo');
			})->strictly()->toThrow('InvalidArgumentException');
		});

		it('should return a string for toJson', function() {
			expect(jur()->request('get')->toJson())->toBe()->a('string');
		});

		it('should return an array for toArray', function() {
			expect(jur()->request('get')->toArray())->toBe()->an('array');
		});

		it('should return an array for toObject', function() {
			expect(jur()->request('get')->toObject())->toBe()->an('object');
		});
	});
?>