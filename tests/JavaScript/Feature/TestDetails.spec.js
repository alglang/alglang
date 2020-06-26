import '../setup';
import { render, fireEvent } from '@testing-library/vue';
import Details from '../../../resources/js/components/Details.vue';
import { expect } from 'chai';

describe('Details.vue', () => {
  it('shows its title', () => {
    const props = { title: 'Test title', pages: [] };

    const { queryByText } = render(Details, { props });

    expect(queryByText('Test title')).to.be.ok;
  });

  it('shows its page names', () => {
    const props = {
      title: '',
      pages: [
        { name: 'foo-bar' },
        { name: 'baz' }
      ]
    };

    const { queryByText } = render(Details, { props });

    expect(queryByText('foo bar')).to.be.ok;
    expect(queryByText('baz')).to.be.ok;
  });

  it('marks the first page as active by default', () => {
    const props = {
      title: '',
      pages: [
        { name: 'foo-bar' },
        { name: 'baz' }
      ]
    };

    const { getByText } = render(Details, { props });
    
    const firstLink = getByText('foo bar');
    const secondLink = getByText('baz');

    expect(firstLink).to.have.class('text-gray-200');
    expect(firstLink).to.have.class('bg-red-700');
    expect(firstLink).to.have.class('hover:text-gray-200');
    expect(secondLink).to.not.have.class('text-gray-200');
    expect(secondLink).to.not.have.class('bg-red-700');
    expect(secondLink).to.not.have.class('hover:text-gray-200');
  });

  it('activates a page when clicked', async () => {
    const props = {
      title: '',
      pages: [
        { name: 'foo-bar' },
        { name: 'baz' }
      ]
    };

    const { getByText } = render(Details, { props });
    const firstLink = getByText('foo bar');
    const secondLink = getByText('baz');
    await fireEvent.click(secondLink);

    expect(firstLink).to.not.have.class('text-gray-200');
    expect(firstLink).to.not.have.class('bg-red-700');
    expect(firstLink).to.not.have.class('hover:text-gray-200');
    expect(secondLink).to.have.class('text-gray-200');
    expect(secondLink).to.have.class('bg-red-700');
    expect(secondLink).to.have.class('hover:text-gray-200');
  });
});
