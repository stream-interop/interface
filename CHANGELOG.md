# Change Log

## 1.0.1

Documentation and tooling only; normative intent and meaning are unchanged from 1.0.0.

- Adopt stardoc for README generation; rewrite docblocks in narrative voice.

- Restructure README interface sections with explicit Properties and Methods subsections.

- Narrow `@param` annotations for static analysis: `SeekableStream::seek($whence)` to `SEEK_CUR|SEEK_SET|SEEK_END`; `StringableStream::subString($length)` to `?int<0,max>`. Runtime signatures unchanged from 1.0.0.

- Editorial: surface the `subString($length = null)` default in the README; typo fix in the filters Q&A; update RFC reference URLs.

## 1.0.0

Stable release.

## 1.0.0-beta2

- Introduce StreamThrowable interface for stream-related exceptions.

- Typographical cleanup.

## 1.0.0-beta1

- Remove AppendableStream; further review indicated it is unnecessary.

- Interface is stabilizing and ready for wide adoption.

## 1.0.0-alpha2

Modifications from public and private review; extending public review period.

- Extract PHPStan aliases to StreamTypeAliases interface.

- Change type aliases from StudlyCaps to snake_case to visually differentiate between classes and non-class type aliases.

- Add marker interfaces for readonly and immutable streams

- Add AppendableStream

## 1.0.0-alpha1

Ready for public review.
