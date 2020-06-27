import '../setup';
import { render, fireEvent } from '@testing-library/vue';
import Details from '../../../resources/js/components/Details.vue';
import Vue from 'vue';
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
    const fooBarComponent = Vue.component('foo-bar', {
      template: '<div>Foo Bar</div>'
    });

    const bazComponent = Vue.component('baz', {
      template: '<div>Baz</div>'
    });

    const props = {
      title: '',
      pages: [
        {
          name: 'foo-bar',
          component: fooBarComponent
        },
        {
          name: 'baz',
          component: bazComponent
        }
      ]
    };

    const { queryByText, getByText } = render(Details, { props });
    expect(queryByText('Foo Bar')).to.be.ok;
    expect(queryByText('Baz')).to.be.null;

    const firstLink = getByText('foo bar');
    const secondLink = getByText('baz');
    await fireEvent.click(secondLink);

    expect(firstLink).to.not.have.class('text-gray-200');
    expect(firstLink).to.not.have.class('bg-red-700');
    expect(firstLink).to.not.have.class('hover:text-gray-200');
    expect(secondLink).to.have.class('text-gray-200');
    expect(secondLink).to.have.class('bg-red-700');
    expect(secondLink).to.have.class('hover:text-gray-200');
    expect(queryByText('Foo Bar')).to.be.null;
    expect(queryByText('Baz')).to.be.ok;
  });
});
